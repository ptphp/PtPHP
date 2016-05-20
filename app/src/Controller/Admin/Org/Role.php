<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/1/19
 * Time: 下午2:53
 */
namespace Controller\Admin\Org;
use Controller\Admin\AbstractAdmin as AbstractAdmin;
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;

class Role extends AbstractAdmin{
    /**
     * 删除
     * @param $id
     * @param $ids
     * @return array
     */
    function action_remove($id,$ids){
        $table = self::table();
        if($id){
            $row = self::_db()->row("select * from $table role_id = ?",$id);
            if(!$row) _throw("记录不存在");
            self::_db()->delete($table,array(
                "id"=>$id
            ));
        }
        if($ids){
            $ids = handle_mysql_in_ids($ids);
            self::_db()->run_sql("delete from $table where role_id in ($ids)");
        }
        return array("msg"=>"删除成功");
    }

    function action_row($id){
        $table = self::table();
        $row = self::_db()->row("select * from $table where role_id = ?",$id);
        return array(
            "row"=>$row
        );
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
        $table = self::table();
        $select_field = "*,role_id as `key`";

        $where = 'where 1 = 1';
        $args = array();
        if(!empty($condition['role_name'])){
            $where .= " and role_name like ?";
            $args[] = "%".$condition['role_name']."%";
        }
//        if(!empty($condition['status'])){
//            $where .= " and status in (?)";
//            $args[] = implode(",",$condition['status']);
//        }

        $count_res = self::_db()->select_row("SELECT COUNT(role_id) AS total FROM $table $where",$args);
        $records = $count_res['total'];
        $total_pages = $records > 0  ? ceil($records/$limit) : 1;
        $skip = ($page - 1) * $limit;

        $sorter_order_tpl = array("ascend"=>"asc","descend"=>"desc");
        if(!empty($sorter)) $sorter = json_decode($sorter,1);
        $sorter_field_tpl = array("ord");
        $sort_field = !empty($sorter['field']) && in_array($sorter['field'],$sorter_field_tpl) ? $sorter['field']  : "role_id";
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
        if(empty($row['role_name'])) _throw("角色名不能为空");
        $_row = array(
            "role_name"=>$row['role_name']
        );

        return array(
            "row"=>$_row
        );
    }
    static function table(){
        return self::_table("org_role");
    }
    function action_update($id,$row){
        $table = self::table();
        $res = self::getSaveRow($row,false);
        self::_db()->update($table,$res['row'],array(
            "role_id"=>$id
        ));
        return array(
            "role_id"=>$id,
            "row"=>$row = self::_db()->row("select *,role_id as `key` from $table where role_id = ?",$id)
        );
    }

    function action_add($row){
        $table = self::table();
        $res = self::getSaveRow($row);
        $id = self::_db()->insert($table,$res['row']);
        return array(
            "role_id"=>$id,
            "row"=>$row = self::_db()->row("select *,role_id as `key` from $table where role_id = ?",$id)
        );
    }
}
