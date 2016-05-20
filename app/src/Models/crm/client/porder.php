<?php
/**
 * 客户预订单
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/2/1
 * Time: 下午5:26
 */
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
class Model_Crm_Client_Porder extends Model{
    const TABLE = "crm_client";
    const TABLE_ORDER = 'crm_client_order';
    const TABLE_PORDER = 'crm_client_porder';
    const TABLE_TRACE = 'crm_client_trace';
    const TABLE_ADVANCE = 'crm_client_advance';
    const TABLE_REVISIT = "crm_client_revisit";

    static function getPorderSaveRow($_row){
        if(empty($_row['client_id'])) _throw("没有选择客户!");
        return array(
            "designer_uid"=>empty($_row['designer_uid'])?null:$_row['designer_uid'],
            "client_id"=>empty($_row['client_id'])?null:$_row['client_id'],
            "nutri"=>empty($_row['nutri'])?null:$_row['nutri'],
            "blood"=>empty($_row['blood'])?null:$_row['blood'],
            "parts"=>empty($_row['parts'])?null:$_row['parts'],
            "material"=>empty($_row['material'])?null:$_row['material'],
            "item"=>empty($_row['item'])?null:$_row['item'],
            "allergies"=>empty($_row['allergies'])?null:$_row['allergies'],
            "years"=>empty($_row['years'])?null:$_row['years'],
            "blood_pressure"=>empty($_row['blood_pressure'])?null:$_row['blood_pressure'],
            "medical_history"=>empty($_row['medical_history'])?null:$_row['medical_history'],
            "medicine"=>empty($_row['medicine'])?null:$_row['medicine'],
            "solution1"=>empty($_row['solution1'])?null:$_row['solution1'],
            "solution2"=>empty($_row['solution2'])?null:$_row['solution2'],
            "request"=>empty($_row['request'])?null:$_row['request'],
        );
    }

    static function get_orderno($table){
        $orderno = "P".date("YmdHis");
        self::_debug(array($table,$orderno));
        $res = self::_db()->select_rows("select id from $table where orderno = ?",$orderno);
        if($res){
            //self::_debug($res);
            sleep(0.5);
            $orderno = self::get_orderno($table);
        }
        return $orderno;
    }

    static function add($_row){
        $row = self::getPorderSaveRow($_row);
        $row['orderno'] = self::get_orderno(self::TABLE_PORDER);
        $row['add_time'] = Utils::date_time_now();
        $row['op_uid'] = Model_Admin_Auth::get_user_id();
        return self::_db()->insert(self::TABLE_PORDER,$row);
    }

    static function update($id,$_row){
        $row = self::getPorderSaveRow($_row);
        self::_db()->update(self::TABLE_PORDER,$row,array(
            "id"=>$id
        ));
        return $id;
    }

    static function remove($id){
        self::_debug(array($id,self::TABLE_PORDER));
        return self::_db()->delete(self::TABLE_PORDER,array(
            "id"=>$id
        ));
    }

    static function list_rows($condition = array(),$pager = array() ,$order = array()){
        $sort_field = !in_array($order['sort_field'],
            array(
                "id",
            )
        ) ? "id":$order['sort_field'];

        $table = self::TABLE_PORDER;
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
        $sql = "SELECT *,id as `key`  FROM $table $where ORDER BY $sort_field $sort_type LIMIT $skip,$limit ";
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

            $designer_uid = $row['designer_uid'];
            $staff_info = Model_Admin_Staff::get_staff_info_by_uid_from_cache($designer_uid);
            if($staff_info){
                $row['designer_name'] = $staff_info['name'];
                $row['designer_avatar'] = $staff_info['avatar'];
            }else{
                $row['designer_name'] = "";
                $row['designer_avatar'] = "";
            }


        }
        return array(
            "total_pages" => $total_pages,
            "total"       => $records,
            "rows"        => $rows,
        );
    }


}