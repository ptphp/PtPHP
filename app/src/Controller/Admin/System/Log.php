<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/3/23
 * Time: 下午5:00
 */
namespace Controller\Admin\System;
use Controller\Admin\AbstractAdmin as AbstractAdmin;
use PtPHP\Model as Model;
use Model_Admin_Auth;
use PtConfig;
use Model_Admin_Permission;
use PtPHP\Utils;

class Log extends AbstractAdmin{

    static function table(){
        return self::_table("sys_log");
    }

    static function pk(){
        return "log_id";
    }


    /**
     * 删除
     * @param $id
     * @param $ids
     * @return array
     */
    function action_remove($id,$ids){
        $pk = self::pk();
        $table = self::table();
        if($id){
            $row = self::_db()->row("select * from $table where $pk = ?",$id);
            if(!$row) _throw("记录不存在");
            self::_db()->delete($table,array(
                $pk=>$id
            ));
        }
        if($ids){
            $ids = handle_mysql_in_ids($ids);
            self::_db()->run_sql("delete from $table where $pk in ($ids)");
        }
        return array("msg"=>"删除成功");
    }

    function action_row($id){
        $pk = self::pk();
        $table = self::table();
        $left_join = self::get_join_sql();
        $row = self::_db()->row("select b.* from $table as l $left_join where l.{$pk} = ?",$id);
        return array(
            "row"=>$row
        );
    }

    static function get_join_sql(){
        $left_join  = "";
        return $left_join;
    }
    function action_list($limit,$page,$sorter,$search,$filters){
        $pk = self::pk();
        $table_alias = "l";
        $table = self::table();
        $select_field = "{$table_alias}.*,{$table_alias}.{$pk} as `key`";

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

        $where = 'where 1 = 1';
        $args = array();
//        if(!empty($condition['mobile'])){
//            $where .= " and {$table_alias}.mobile like ?";
//            $args[] = "%".$condition['mobile'];
//        }
        if(!empty($condition['user_id'])){
            $where .= " and {$table_alias}.user_id = ?";
            $args[] = $condition['user_id'];
        }
        $count_res = self::_db()->select_row("SELECT COUNT({$table_alias}.{$pk}) AS total FROM $table as {$table_alias} $where",$args);
        $records = $count_res['total'];
        $total_pages = $records > 0  ? ceil($records/$limit) : 1;
        $skip = ($page - 1) * $limit;

        $sorter_order_tpl = array("ascend"=>"asc","descend"=>"desc");
        if(!empty($sorter)) $sorter = json_decode($sorter,1);
        $sorter_field_tpl = array("ord");
        $sort_field = !empty($sorter['field']) && in_array($sorter['field'],$sorter_field_tpl) ? $sorter['field']  : $table_alias.".".$pk;
        $sort_order = !empty($sorter['order']) && !empty($sorter_order_tpl[$sorter['order']]) ? $sorter_order_tpl[$sorter['order']]:"desc";
        $order  = "ORDER BY $sort_field $sort_order";
        $left_join = self::get_join_sql();
        $sql = "SELECT $select_field FROM $table as {$table_alias} $left_join $where $order LIMIT $skip,$limit ";
        //self::_debug($sql);
        $rows = self::_db()->rows($sql,$args);

        $res = array(
            "total"=>$records,
            "page"=>$page,
            "total_pages"=>$total_pages,
            "limit"=>$limit,
            "skip"=>$skip,
            "rows"=>$rows,
        );
        if(!self::is_production())
            $res['debug'] = array(
                "sql"=>$sql,
                "args"=>$args,
                "params"=>array($limit,$page,$sorter,$search,$filters,$condition),
            );
        return $res;
    }

    static function add($content,$method,$user_id,$ip = null){
        self::_db()->insert(self::table(),array(
            "content"=>$content,
            "method"=>$method,
            "ip"=>$ip,
            "user_id"=>$user_id,
            "add_time"=>Utils::date_time_now(),
        ));
    }
}