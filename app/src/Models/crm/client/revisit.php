<?php
/**
 * 客户回访
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/2/1
 * Time: 下午5:26
 */
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
class Model_Crm_Client_Revisit extends Model{
    const TABLE_REVISIT = "crm_client_revisit";
    static function getSaveRow($_row){
        if(empty($_row['client_id'])) _throw("没有选择客户!");
        return array(
            "client_id"=>$_row['client_id'],
            "type"=>empty($_row['type']) ? null:$_row['type'],
            "content"=>empty($_row['content']) ? null:$_row['content'],
            "dtime"=>empty($_row['dtime']) ? null:$_row['dtime'],
            "ndtime"=>empty($_row['ndtime']) ? null:$_row['ndtime'],
        );
    }

    static function add($_row){
        $row = self::getSaveRow($_row);
        $row['add_time'] = Utils::date_time_now();
        $row['op_uid'] = Model_Admin_Auth::get_user_id();
        return self::_db()->insert(self::TABLE_REVISIT,$row);
    }

    static function update($id,$_row){
        $row = self::getSaveRow($_row);
        self::_db()->update(self::TABLE_REVISIT,$row,array(
            "id"=>$id
        ));
        return $id;
    }

    static function remove($id){
        self::_debug(array($id,self::TABLE_REVISIT));
        return self::_db()->delete(self::TABLE_REVISIT,array(
            "id"=>$id
        ));
    }
    static function list_rows($condition = array(),$pager = array() ,$order = array()){
        $sort_field = !in_array($order['sort_field'],
            array(
                "id",
            )
        ) ? "id":$order['sort_field'];

        $table = self::TABLE_REVISIT;
        $where = 'where 1= 1';
        $args = array();
        if(!empty($condition['client_id'])){
            $where .= " and client_id = ?";
            $args[] = $condition['client_id'];
        }
//        if(!empty($condition['name'])){
//            $where .= " and name like ?";
//            $args[] = "%".$condition['name']."%";
//        }

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
        }
        return array(
            "total_pages" => $total_pages,
            "total"       => $records,
            "rows"        => $rows,
        );
    }



}