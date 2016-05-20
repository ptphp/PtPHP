<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/1/19
 * Time: 下午2:53
 */
namespace Controller\Admin\Dict;
use Controller\Admin\AbstractAdmin as AbstractAdmin;
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;

class Area extends AbstractAdmin{

    static function table(){
        return self::_table("dict_area");
    }
    /**
     * 删除
     * @param $id
     * @param $ids
     * @return array
     */
    function action_remove($id,$ids){
        $table = self::table();
        if($id){
            $row = self::_db()->row("select * from $table area_id = ?",$id);
            if(!$row) _throw("记录不存在");
            self::_db()->delete($table,array(
                "id"=>$id
            ));
        }
        if($ids){
            $ids = handle_mysql_in_ids($ids);
            self::_db()->run_sql("delete from $table where area_id in ($ids)");
        }
        return array("msg"=>"删除成功");
    }

    function action_row($id){
        $table = self::table();
        $row = self::get_detail($id);
        return array(
            "row"=>$row
        );
    }
    static function get_join_sql(){
//        $table_department = self::_table("org_department");
//        $table_role = self::_table("org_role");
//        $table_position = self::_table("org_position");
//        $left_join  = "left join $table_department as d on d.dep_id = s.dep_id ";
//        $left_join .= "left join $table_position as p on p.pot_id = s.pot_id ";
//        $left_join .= "left join $table_role as r on r.role_id = s.role_id ";
        return $left_join = "";
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
        $select_field = "*,area_id as `key`";

        $where = 'where 1 = 1';
        $args = array();
        if(!empty($condition['area_name'])){
            $where .= " and area_name like ?";
            $args[] = "%".$condition['area_name']."%";
        }

//        if(!empty($condition['status'])){
//            $where .= " and status in (?)";
//            $args[] = implode(",",$condition['status']);
//        }
        $table_role = self::_table("org_role");
        $count_res = self::_db()->select_row("SELECT COUNT(area_id) AS total FROM $table $where",$args);
        $records = $count_res['total'];
        $total_pages = $records > 0  ? ceil($records/$limit) : 1;
        $skip = ($page - 1) * $limit;

        $sorter_order_tpl = array("ascend"=>"asc","descend"=>"desc");
        if(!empty($sorter)) $sorter = json_decode($sorter,1);
        $sorter_field_tpl = array("ord");
        $sort_field = !empty($sorter['field']) && in_array($sorter['field'],$sorter_field_tpl) ? $sorter['field']  : "area_id";
        $sort_order = !empty($sorter['order']) && !empty($sorter_order_tpl[$sorter['order']]) ? $sorter_order_tpl[$sorter['order']]:"desc";
        $order  = "ORDER BY $sort_field $sort_order";
        $left_join = self::get_join_sql();
        $sql = "SELECT $select_field FROM $table $left_join $where $order LIMIT $skip,$limit ";
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
        if(empty($row['area_name'])) _throw("地区不能为空");

        $_row = array(
            "area_name"=>$row['area_name']
        );
        return array(
            "row"=>$_row
        );
    }
    function action_update($id,$row){
        $table = self::table();
        $res = self::getSaveRow($row,false);
        self::_db()->update($table,$res['row'],array(
            "area_id"=>$id
        ));
        return array(
            "area_id"=>$id,
            "row"=>self::get_detail($id)
        );
    }
    static function get_detail($id){
        $table = self::table();
        $row = self::_db()->row("select *,area_id as `key` from $table where area_id = ?",$id);
        return $row;
    }

    function action_add($row){
        $table = self::table();
        $res = self::getSaveRow($row);
        $id = self::_db()->insert($table,$res['row']);
        return array(
            "area_id"=>$id,
            "row"=>self::get_detail($id)
        );
    }
}
