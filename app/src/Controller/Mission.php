<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/1/19
 * Time: 下午2:53
 */
namespace Controller;

use PtPHP\Model as Model;
use Controller\Mission\Auth as Auth;

use PtPHP\Utils as Utils;
use Model_Session;
use Model_Auth;
use Model_Admin_Auth;
use PtConfig;
class Mission extends Model{
    function __construct(){}

    function action_list(){
        $table_mission = self::_table("mission");
        $rows = self::_db()->rows("select id,title,`desc`,award,thumb,is_rec from $table_mission
                    where status = 1 and unix_timestamp( now() ) between unix_timestamp( start_time) and unix_timestamp( end_time )  order by is_rec desc, ord desc ,id desc");
        return array(
            "missions"=>$rows,
        );
    }
    static function get_user_avatar($user_id){
        $pic = "";
        return $pic;
    }
    function action_detail($id){
        $table_mission      = self::_table("mission");
        $table_user_mission = self::_table("user_mission");
        $table_mission_verify = self::_table("user_mission_verify");
        $table_mission_task = self::_table("mission_task");
        $table_user_wx      = self::_table("user_wx");
        $table_user_wx_rel  = self::_table("user_wx_rel");
        //任务详情
        $mission = self::_db()->row("select * from $table_mission where id = ?",$id);
        $mission['tips1'] = null;
        if(!empty($mission['start_time']) && time() < strtotime($mission['start_time'])) $mission['tips1'] = "任务未开始";
        if(!empty($mission['end_time']) && time() > strtotime($mission['end_time'])) $mission['tips1'] = "任务已结束";

        //参与总人数
        $join_users_count = self::_db()->row("select count(id) as total from $table_user_mission where mission_id = ?",$id);
        $join_users_total = $join_users_count['total'];
        //取最新的14个参与人头像
        $limit = 14;
        $join_users = self::_db()->rows("select user_id from $table_user_mission where mission_id = ? order by id desc limit $limit",$id);
        $avatars = array();
        foreach($join_users as $join_user){
            $avatar = self::_db()->row("select rel.user_id,wx.avatar as pic from $table_user_wx_rel as rel
                          left join $table_user_wx as wx on wx.openid = rel.openid
                          where rel.user_id = ?",$join_user['user_id']);
            if($avatar) $avatars[] = $avatar;
        }
        //取子任务
        $tasks = self::_db()->rows("select * from $table_mission_task where mission_id = ? order by id asc",$id);
        $i = 1;
        foreach($tasks as &$task){
            $task['key'] =$i++;
        }

        $user_id = Auth::get_user_id();
        $user_mission  = array();
        $user_missions = array();
        if($user_id > 0){
            //当前用户 参与的任务
            $user_mission = self::_db()->row("select * from $table_user_mission where mission_id = ? and user_id = ?",$id,$user_id);
            //当前用户 最近提交审核的任务
            $user_missions = self::_db()->rows("select * from $table_mission_verify where mission_id = ? and user_id = ? order by `task_key` asc ",$id,$user_id);
        }
        return array(
            "mission"          => $mission,
            "avatars"          => $avatars,
            "join_users_total" => $join_users_total,
            "tasks"            => $tasks,
            "user_mission"     => $user_mission,
            "user_missions"    => $user_missions,
        );
    }
    function action_example($task_id){
        $table_mission_task = self::_table("mission_task");
        $row = self::_db()->row("select example from $table_mission_task where id = ?",$task_id);
        return array(
            'example'=>$row['example']
        );
    }
}
