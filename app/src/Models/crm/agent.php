<?php
/**
 * 代理
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/2/1
 * Time: 下午5:26
 */
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
class Model_Crm_Agent extends Model{
    const TABLE = "crm_agent";
    const TABLE_TRACE = 'crm_agent_trace';
    const KEY = "crm_a_1";
    static function cache_row($id,$row){
        self::_redis()->setex(self::KEY.$id,60,json_encode($row));
    }
    static function detail($id){
        $detail = self::_redis()->get(self::KEY.$id);
        if(!$detail){
            $table = self::TABLE;
            $detail = self::_db()->select_row("select * from $table where id = ? and is_del = 0",$id);
            if($detail) self::cache_row($id,$detail);
        }else{
            $detail = json_decode($detail,1);
        }
        return $detail;
    }
    static function getSaveRow($_row){
        if(empty($_row['com_name'])) _throw("企业名不能为空!");
        if(empty($_row['province_id'])) _throw("所属省份不能为空!");
        return array(
            "com_name"=>empty($_row['com_name'])?null:$_row['com_name'],
            "com_charger"=>empty($_row['com_charger'])?null:$_row['com_charger'],
            "tel"=>empty($_row['tel'])?null:$_row['tel'],
            "sex"=>empty($_row['sex']) ? 0 : 1,
            "addr"=>empty($_row['addr'])?null:$_row['addr'],
            "area_id"=>empty($_row['area_id'])?null:$_row['area_id'],
            "province_id"=>empty($_row['province_id'])?null:$_row['province_id'],
            "zxs_uid"=>intval(empty($_row['zxs_uid'])?null:$_row['zxs_uid']),
            "zxz_uid"=>intval(empty($_row['zxz_uid'])?null:$_row['zxz_uid']),
            "kf_uid"=>intval(empty($_row['kf_uid'])?null:$_row['kf_uid']),
        );
    }
    static function add($_row){
        $row = self::getSaveRow($_row);
        $row['add_time'] = Utils::date_time_now();
        $row['op_uid'] = Model_Admin_Auth::get_user_id();
        $id =  self::_db()->insert(self::TABLE,$row);
        $row['id'] = $id;
        self::cache_row($id,$row);
        return $id;
    }
    static function remove($id){
        $row['del_time'] = Utils::date_time_now();
        $row['is_del'] = 1;
        self::_db()->update(self::TABLE,$row,array(
            "id"=>$id
        ));
        self::_redis()->del(self::KEY.$id);
    }
    static function update($id,$_row){
        $row = self::getSaveRow($_row);
        self::_db()->update(self::TABLE,$row,array("id"=>$id));
        self::cache_row($id,$_row);
    }
    static function row($id){
        $table = self::TABLE;
        $row = self::_db()->select_row("select * from $table where id = ? and is_del = 0",$id);
        if($row){
            $staff_info = Model_Admin_Staff::get_staff_info_by_uid_from_cache($row['op_uid']);
            $row['op_name'] = $staff_info['name'];
            $row['op_avatar'] = $staff_info['avatar'];

            $staff_info = Model_Admin_Staff::get_staff_info_by_uid_from_cache($row['zxs_uid']);
            $row['zxs_name'] = $staff_info['name'];
            $row['zxs_avatar'] = $staff_info['avatar'];

            $staff_info = Model_Admin_Staff::get_staff_info_by_uid_from_cache($row['zxz_uid']);
            $row['zxz_name'] = $staff_info['name'];
            $row['zxz_avatar'] = $staff_info['avatar'];

            $staff_info = Model_Admin_Staff::get_staff_info_by_uid_from_cache($row['kf_uid']);
            $row['kf_name'] = $staff_info['name'];
            $row['kf_avatar'] = $staff_info['avatar'];
        }
        return $row;
    }
    static function list_rows($condition = array(),$pager = array() ,$order = array()){
        $sort_field = !in_array($order['sort_field'],
            array(
                "id",
                "name",
            )
        ) ? "id":$order['sort_field'];

        $table = self::TABLE;
        $table_alias = "a";
        $sort_field = $table_alias.".".$sort_field;
        $where = "where $table_alias.is_del = 0";
        $args = array();

        if(!empty($condition['com_name'])){
            $where .= " and $table_alias.com_name like ?";
            $args[] = "%".$condition['com_name']."%";
        }
        if(!empty($condition['com_charger'])){
            $where .= " and $table_alias.com_charger like ?";
            $args[] = "%".$condition['com_charger']."%";
        }
        if(!empty($condition['tel'])){
            $where .= " and $table_alias.tel like ?";
            $args[] = "%".$condition['tel']."%";
        }
        if(!empty($condition['zxs_uid'])){
            $where .= " and $table_alias.zxs_uid = ?";
            $args[] = $condition['zxs_uid'];
        }
        if(!empty($condition['kf_uid'])){
            $where .= " and $table_alias.kf_uid = ?";
            $args[] = $condition['kf_uid'];
        }
        if(!empty($condition['area_id'])){
            $where .= " and $table_alias.area_id = ?";
            $args[] = $condition['area_id'];
        }
        if(!empty($condition['province_id'])){
            $where .= " and $table_alias.province_id = ?";
            $args[] = $condition['province_id'];
        }
        if(!empty($condition['start_time'])){
            $where .= " and $table_alias.add_time >= ?";
            $args[] = $condition['start_time']." 00:01";
        }

        if(!empty($condition['end_time'])){
            $where .= " and $table_alias.add_time <= ?";
            $args[] = $condition['end_time']." 23:59";
        }

        $user_id = Model_Admin_Auth::get_user_id();

        if($user_id > 0){
            $uids = Model_Admin_Staff::get_sub_position_uids($user_id);
            $uids[] = $user_id;
            $where .= " and ($table_alias.op_uid in (".implode(",",$uids).") or
                             $table_alias.zxs_uid = ? or
                             $table_alias.zxz_uid = ? or
                             $table_alias.kf_uid = ?)";
            $args[] = $user_id;
            $args[] = $user_id;
            $args[] = $user_id;
        }

        $sort_type_tpl = array(
            "ascend"=>"asc",
            "descend"=>"desc"
        );
        $sort_type = !in_array($order['sort_type'],array_keys($sort_type_tpl)) ? "desc":$sort_type_tpl[$order['sort_type']];
        //_throw($sort_type);
        $count_res = self::_db()->select_row("SELECT COUNT($table_alias.id) AS total FROM $table as $table_alias $where",$args);
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
        $sql = "SELECT $table_alias.*,$table_alias.id as `key`,$table_alias.id FROM $table as $table_alias $where ORDER BY $sort_field $sort_type LIMIT $skip,$limit ";
        //_throw($sql);
        //self::_debug(array($sql,$args,$condition,$pager,$order,$sort_field,$sort_type));
        $rows = self::_db()->select_rows($sql,$args);
        foreach($rows as &$row){
            $row['key'] = $row['id'];
            $row['area_name'] = Model_Crm_Area::detail($row['area_id']);
            $row['province_name'] = Model_Province::detail($row['province_id']);

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
    static function list_note_rows($condition = array(),$pager = array() ,$order = array()){
        $sort_field = !in_array($order['sort_field'],
            array(
                "id",
            )
        ) ? "id":$order['sort_field'];

        $table = "crm_agent_trace";
        $table = self::TABLE_TRACE;
        $where = 'where 1= 1';
        $args = array();
        if(!empty($condition['agent_id'])){
            $where .= " and agent_id = ?";
            $args[] = $condition['agent_id'];
        }
        if(!empty($condition['name'])){
            $where .= " and name like ?";
            $args[] = "%".$condition['name']."%";
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
    static function note_add($id,$note,$op_uid){
        $row = array(
            "note"         => $note,
            "ip"           => Utils::ip(),
            "agent_id"     => $id,
            "op_uid"       => $op_uid,
            "add_time"     => Utils::date_time_now(),
        );
        return self::_db()->insert("crm_agent_trace",$row);
    }
}