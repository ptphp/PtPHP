<?php
namespace Controller\Mission;
use PtPHP\Model as Model;
use Controller\Mission\Auth as Auth;
use Model_Session;
use PtPHP\Utils;

class Task extends Model{
    function action_begin_task($mission_id){
        $user_id = Auth::get_user_id();
        $mission_table = self::_table("mission");
        if(!$user_id) _throw("您还没有登陆",9001);

        $mission = self::_db()->row("select * from $mission_table where id = ?",$mission_id);
        if($mission['remain_times'] == 0) _throw("任务参与人数已满");

        if(!empty($mission['start_time']) && time() < strtotime($mission['start_time'])) _throw("任务还没有开始");
        if(!empty($mission['end_time']) && time() > strtotime($mission['end_time'])) _throw("任务已结束");

        $table = self::_table("user_mission");
        $row = self::_db()->row("select * from $table where user_id = ? and mission_id = ?",$user_id,$mission_id);
        if($row) _throw("您已参与过了");

        self::_db()->insert($table,array(
            "user_id"=>$user_id,
            "mission_id"=>$mission_id,
            "task_key"=>1,
            "begin_time"=>Utils::date_time_now()
        ));


        self::_db()->run_sql("update $mission_table set join_nums = join_nums + 1,remain_times = remain_times -1 where id = ?",$mission_id);

        $res = array(
            "msg"=>"参与成功",
        );
        if(!self::is_production()){
            $res['debug'] = array(
                "sql"=>self::_db()->get_run_stack()
            );
        }
        return $res;
    }
    function action_do_verify($mission_id,$task_key,$pics,$note){
        $task_key = intval($task_key);
        if(!$task_key) _throw("task_key 不能为空");
        $user_id = Auth::get_user_id();
        if(!$user_id) _throw("您还没有登陆",9001);
        $table = self::_table("user_mission");
        $row = self::_db()->row("select * from $table where user_id = ? and mission_id = ?",$user_id,$mission_id);
        if(!$row) _throw("您还没有参与过此任务");

        if($row['task_key'] != $task_key) _throw("请按数序提交任务审核");

        $table_verify = self::_table("user_mission_verify");

        $_pics = array();
        if(1){
            $pics = explode("|",$pics);
            foreach($pics as $pic){
                $_pics[] = \Controller\Mission\Tool::upload_content($pic);
            }
        }
        $_pics = implode("|",$_pics);
        self::_debug($_pics);
        $verify_id = self::_db()->insert($table_verify,array(
            "user_id"=>$user_id,
            "mission_id"=>$mission_id,
            "task_key"=>$task_key,
            "pics"=>$_pics,
            "note"=>$note,
            "add_time"=>Utils::date_time_now()
        ));

        self::_db()->update($table,array(
            "verify_id"=>$verify_id,
        ),array("id"=>$row['id']));

        return array(
            "msg"=>"提交审核成功"
        );
    }


    function action_my_task($status){
        $user_id = Auth::get_user_id();
        if(!$user_id) _throw("您还没有登陆",9001);

        $table = self::_table("user_mission");
        $table_mission = self::_table("mission");
        $rows = self::_db()->rows("select
                      user_mission.*,mission.title,mission.thumb,mission.desc from $table as user_mission
                      left join $table_mission as mission on mission.id = user_mission.mission_id
                      where user_mission.user_id = ? order by user_mission.id desc ",$user_id);

        return array(
            "missions"=>$rows
        );
    }
}
