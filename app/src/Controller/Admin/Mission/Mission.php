<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/1/19
 * Time: 下午2:53
 */
namespace Controller\Admin\Mission;
use Controller\Admin\AbstractAdmin as AbstractAdmin;

use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
use Symfony\Component\Config\Definition\Exception\Exception;

class Mission extends AbstractAdmin{
    function action_remove($id){
        $row = self::_db()->row("select * from ldt_mission where id = ?",$id);
        if(!$row) _throw("记录不存在");
        if($row['join_nums'] > 0) _throw("不能删除已有人员参与的任务");
        self::_db()->delete("ldt_mission",array(
            "id"=>$id
        ));
        return array("msg"=>"删除成功");
    }

    function action_remove_task($id){
        self::_db()->delete("ldt_mission_task",array(
            "id"=>$id
        ));
        return array("msg"=>"删除成功");
    }

    function action_row($id){
        $row = self::_db()->row("select * from ldt_mission where id = ?",$id);
        $tasks = self::_db()->rows("select * from ldt_mission_task where mission_id = ? order by id asc",$id);
        $users = self::_db()->rows("select um.*,um.id as `key`,wx.avatar,wx.nickname as name from ldt_user_mission as um
                left join ldt_user_wx_rel as rel on rel.user_id = um.user_id
                left join ldt_user_wx as wx on wx.openid = rel.openid
                where um.mission_id = ? order by um.id desc",$id);
        $i = 1;
        foreach($tasks as &$task){
            $task['key'] = $i++;
        }
        return array("row"=>$row,"tasks"=>$tasks,"users"=>$users);
    }
    function action_list($limit,$page,$sorter,$search,$filters){
        $limit = empty($limit) ? 10 : intval($limit);
        $page  = empty($page)  ? 1  : intval($page);
        $condition = array();
        if(!empty($search)){
            self::_debug($search);
            $condition = json_decode($search,1);
        }
        self::_debug($condition);
        if(!empty($filters)){
            $filters = json_decode($filters,1);
            self::_debug($filters);
            $condition = array_merge($condition,$filters);
        }
        self::_debug($condition);
        $table = self::_table("mission");
        $select_field = "id as `key`,id,title,`desc`,status,ord,join_nums,finish_nums";

        $where = 'where 1 = 1';
        $args = array();
        if(!empty($condition['title'])){
            $where .= " and title like ?";
            $args[] = "%".$condition['title']."%";
        }
        if(!empty($condition['status'])){
            $where .= " and status in (?)";
            $args[] = implode(",",$condition['status']);
        }

        $count_res = self::_db()->select_row("SELECT COUNT(id) AS total FROM $table $where",$args);
        $records = $count_res['total'];
        $total_pages = $records > 0  ? ceil($records/$limit) : 1;
        $skip = ($page - 1) * $limit;

        $sorter_order_tpl = array("ascend"=>"asc","descend"=>"desc");
        if(!empty($sorter)) $sorter = json_decode($sorter,1);
        $sorter_field_tpl = array("ord");
        $sort_field = !empty($sorter['field']) && in_array($sorter['field'],$sorter_field_tpl) ? $sorter['field']  : "id";
        $sort_order = !empty($sorter['order']) && !empty($sorter_order_tpl[$sorter['order']]) ? $sorter_order_tpl[$sorter['order']]:"desc";
        $order  = "ORDER BY $sort_field $sort_order";

        $sql = "SELECT $select_field  FROM $table $where $order LIMIT $skip,$limit ";
        //self::_debug($sql);
        $rows = self::_db()->rows($sql,$args);
        $res = array(
            "total"=>$records,
            "page"=>$page,
            "total_pages"=>$total_pages,
            "limit"=>$limit,
            "skip"=>$skip,
            "rows"=>$rows
        );
        if(!self::is_production())
            $res['debug'] = array(
                "sql"=>$sql,
                "args"=>$args,
                "params"=>array($limit,$page,$sorter,$search,$filters,$condition),
            );
        return $res;
    }
    static function getSaveRow($row,$is_add = true){
        $row = json_decode($row,1);
        if(empty($row['title'])) _throw("任务名称不能为空");
        if(empty($row['desc'])) _throw("任务描述不能为空");
        if(empty($row['type'])) _throw("任务类型不能为空");
        if(!isset($row['tag']) || $row['tag'] === "0" || $row['tag'] === "null"){
            _throw("任务标签不能为空");
        }
        if(empty($row['platform_name'])) _throw("详情页平台名不能为空");
        if(empty($row['com_name'])) _throw("商家名称不能为空");
        if(empty($row['award'])) _throw("奖励绿电金额不能为空");
        if(empty($row['start_time'])) _throw("有效开始时间不能为空");
        if(empty($row['start_time'])) _throw("有效结束时间不能为空");

        if($is_add && empty($row['remain_times'])) _throw("任务剩余次数必须填写大于0的整数");

        //if(empty($row['status'])) _throw("状态不能为空");
        $_row = array(
            "title"=>empty($row['title']) ? "" : $row['title'],
            "desc"=>empty($row['desc']) ? "" : $row['desc'],
            "com_name"=>empty($row['com_name']) ? "" : $row['com_name'],
            "tag"=>empty($row['tag']) ? null : $row['tag'],
            "type"=>empty($row['type']) ? null : $row['type'],
            "thumb"=>empty($row['thumb']) ? "" : $row['thumb'],
            "btn_name"=>empty($row['btn_name']) ? "" : $row['btn_name'],
            "sno"=>empty($row['sno']) ? "" : $row['sno'],
            "platform"=>empty($row['platform']) ? null : $row['platform'],
            "platform_name"=>empty($row['platform_name']) ? "" : $row['platform_name'],
            "wechat_account"=>empty($row['wechat_account']) ? "" : $row['wechat_account'],
            "award"=>empty($row['award']) ? null : $row['award'],
            "start_time"=>empty($row['start_time']) ? null : $row['start_time'],
            "end_time"=>empty($row['end_time']) ? null : $row['end_time'],
            "tips"=>empty($row['tips']) ? "" : $row['tips'],
            "example"=>empty($row['example']) ? "" : $row['example'],
            "status"=>empty($row['status']) ? null : $row['status'],
            "is_rec"=>empty($row['is_rec']) ? 0 : $row['is_rec'],
            "note"=>empty($row['note']) ? "" : $row['note'],
            "ord"=>empty($row['ord']) ? 0 : $row['ord'],
        );
        if($is_add){
            $_row['remain_times'] = $row['remain_times'];
        }
        $tasks = empty($row['tasks']) ? array():$row['tasks'];
        foreach($tasks as $task){
            if(empty($task['title'])) _throw("子任务:".$task['key'].",任务描述不能为空");
        }

        return array(
            "row"=>$_row,
            "tasks"=>$tasks
        );
    }
    function action_update($id,$row){
        $res = self::getSaveRow($row,false);
        self::_db()->update("ldt_mission",$res['row'],array(
            "id"=>$id
        ));
        if(!empty($res['tasks'])){
            $tasks = $res['tasks'];
            foreach($tasks as $task){
                $task['mission_id'] = $id;
                if(!empty($task['id'])){
                    $tid = $task['id'];
                    unset($task['id']);
                    self::_db()->update("ldt_mission_task",$task,array("id"=>$tid));
                }else{
                    unset($task['id']);
                    self::_db()->insert("ldt_mission_task",$task);
                }
            }
        }
        return array("id"=>$id);
    }
    function action_save_task_example($task_id,$example){
        $table = self::_table("mission_task");
        self::_db()->update($table,array("example"=>$example),array("id"=>$task_id));
    }
    static function handleMissionSuccess($user_id,$mission_id,$task_key){
        $table = self::_table("mission_task");
        $table_mission_award_log = self::_table("mission_award_log");
        $row = self::_db()->row("select award from $table where mission_id = ? and `key` = ?",$mission_id,$task_key);
        $award = $row['award'];

        $log_id = self::_db()->insert($table_mission_award_log,array(
            "award"=>$award,
            "user_id"=>$user_id,
            "mission_id"=>$mission_id,
            "task_key"=>$task_key,
            "add_time"=>Utils::date_time_now(),
            "status"=>0,
        ));

        $key = md5("ldt2016");
        $data = array();
        $data['user_id'] = $user_id;
        $data['mission_id'] = $mission_id;
        $data['task_key'] = $task_key;
        $data['award'] = $award;
        $data['sign'] = md5($key.http_build_query($data));

        $url = MISSION_ACCOUNT_API_URL."&".http_build_query($data);
        try{
            $res = file_get_contents($url);
            if(!$res) _throw("接口异常");
        }catch(\Exception $e){
            self::_db()->update($table_mission_award_log,array("status"=>2),array("id"=>$log_id));
            _throw($e->getMessage());
        }
        self::_db()->update($table_mission_award_log,array("status"=>1),array("id"=>$log_id));
        $res = json_decode($res,1);
        if($res['error']) _throw($res['result']);
    }
    function action_do_verify($verify_id,$status,$reason){
        $table = self::_table("user_mission_verify");
        $table_user_mission = self::_table("user_mission");
        $table_mission = self::_table("mission");
        $table_mission_task = self::_table("mission_task");
        $row = self::_db()->row("select * from $table where id = ?",$verify_id);

        $user_id = $row['user_id'];
        $mission_id = $row['mission_id'];
        if($status == 1){
            $task_count = self::_db()->row("select count(id) as total from $table_mission_task where mission_id = ?",$mission_id);
            $task_count_total = $task_count['total'];
            self::handleMissionSuccess($user_id,$mission_id,$row['task_key']);
            $user_mission = array();
            if($task_count_total == $row['task_key']){
                $user_mission['finish_time'] = Utils::date_time_now();
                self::_db()->run_sql("update $table_mission set finish_nums = finish_nums + 1 where id = ?",$mission_id);
            }else{
                $user_mission['task_key'] = $row['task_key'] + 1;
                $user_mission['verify_id'] = null;
            }
            self::_db()->update($table_user_mission,$user_mission,array("user_id"=>$user_id,"mission_id"=>$mission_id));
        }
        self::_db()->update($table,array("up_time"=>Utils::date_time_now(),"status"=>$status,"reason"=>$reason),array("id"=>$verify_id));
        return array(
            "user_id"=>$user_id,
            "mission_id"=>$mission_id,
            "task_key"=>$row['task_key'],
        );
    }
    function action_get_verify_task($user_id,$mission_id,$task_key){
        $table = self::_table("user_mission_verify");
        $table_task = self::_table("mission_task");
        $rows = self::_db()->rows("select v.*,t.title,t.award from $table as v
            left join $table_task as t on t.mission_id = v.mission_id and t.`key` = v.task_key
            where v.user_id = ? and v.mission_id = ? and v.task_key = ? order by v.id asc",$user_id,$mission_id,$task_key);
        return array(
            "tasks"=>$rows
        );
    }
    function action_add($row){
        $res = self::getSaveRow($row);
        $res['row']['add_time'] = Utils::date_time_now();
        $id = self::_db()->insert("ldt_mission",$res['row']);
        if(!empty($res['tasks'])){
            $tasks = $res['tasks'];
            foreach($tasks as &$task){
                unset($task['id']);
                $task['mission_id'] = $id;
            }
            //print_r($tasks);
            self::_db()->insert("ldt_mission_task",$tasks);
        }
        return array("id"=>$id);
    }
}
