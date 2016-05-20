<?php
/**
 * 客户追加信息
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/2/1
 * Time: 下午5:26
 */
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
class Model_Crm_Client_Trace extends Model{
    const TABLE = "crm_client";
    const TABLE_ORDER = 'crm_client_order';
    const TABLE_PORDER = 'crm_client_porder';
    const TABLE_TRACE = 'crm_client_trace';
    const TABLE_ADVANCE = 'crm_client_advance';
    const TABLE_REVISIT = "crm_client_revisit";

    static function list_rows($condition = array(),$pager = array() ,$order = array()){
        $sort_field = !in_array($order['sort_field'],
            array(
                "id",
            )
        ) ? "id":$order['sort_field'];

        $table = self::TABLE_TRACE;
        $where = 'where 1= 1';
        $args = array();
        if(!empty($condition['client_id'])){
            $where .= " and client_id = ?";
            $args[] = $condition['client_id'];
        }
        $sort_type_tpl = array(
            "ascend"=>"asc",
            "descend"=>"desc"
        );
        $sort_type = !in_array($order['sort_type'],array_keys($sort_type_tpl)) ? "desc":$sort_type_tpl[$order['sort_type']];
        //_throw($sort_type);
        $count_res = self::_db()->select_row("SELECT COUNT(id) AS total FROM $table $where",$args);
        $records = $count_res['total'];
        $page = $pager['page'];
        $limit = $pager['limit'];
        if( $records > 0 ) {
            $total_pages = ceil($records/$limit);
        }
        else {
            $total_pages = 1;
        }
        $skip = ($page - 1) * $limit;
        $sql = "SELECT note,add_time,id as `key`,id,op_uid  FROM $table $where ORDER BY $sort_field $sort_type LIMIT $skip,$limit ";
        //_throw($sql);
        //self::_debug(array($sql,$args,$condition,$pager,$order,$sort_field,$sort_type));
        $rows = self::_db()->select_rows($sql,$args);
        foreach($rows as &$row){
            $op_uid = $row['op_uid'];
            $staff_info = Model_Admin_Staff::get_staff_info_by_uid_from_cache($op_uid);
            if($staff_info){
                $row['op_name'] = $staff_info['name'];
                $row['op_avatar'] = $staff_info['avatar'];
            }else{
                $row['op_name'] = "";
                $row['op_avatar'] = "";
            }
        }
        return array(
            "total_pages" => $total_pages,
            "total"       => $records,
            "rows"        => $rows,
        );
    }

    static function add($id,$note,$user_id){
        $table = self::TABLE_TRACE;
        $row = array(
            "note"         => $note,
            "ip"           => Utils::ip(),
            "client_id"    => $id,
            "op_uid"       => $user_id,
            "add_time"     => Utils::date_time_now(),
        );
        return self::_db()->insert($table,$row);
    }

}