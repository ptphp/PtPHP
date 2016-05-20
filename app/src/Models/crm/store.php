<?php
/**
 * 店铺
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/2/1
 * Time: 下午5:26
 */
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
class Model_Crm_Store extends Model{
    const TABLE = "crm_store";
    const TABLE_ADVANCE = "crm_store_advance";
    const TABLE_TRACE = "crm_store_trace";
    const KEY = "crm_sto_";
    static function cache_row($id,$row){
        self::_redis()->set(self::KEY.$id,json_encode($row));
    }
    static function detail($id){
        $detail = self::_redis()->get(self::KEY.$id);
        if(1 || !$detail){
            $table = self::TABLE;
            $detail = self::_db()->select_row("select * from $table where id = ? and is_del = 0",$id);
            if($detail){
                if($detail['agent_id']){
                    $agent_info = Model_Crm_Agent::detail($detail['agent_id']);
                    if($agent_info){
                        $detail['area_id'] = $agent_info['area_id'];
                        $detail['province_id'] = $agent_info['province_id'];
                    }
                }
                self::cache_row($id,$detail);
            }
        }else{
            $detail = json_decode($detail,1);
        }
        return $detail;
    }
    static function getSaveRow($_row){
        if(empty($_row['name'])) _throw("店名不能为空!");
        #todo
        if(!empty($_row['agent_id'])){
            $agent_info = Model_Crm_Agent::detail($_row['agent_id']);
            if($agent_info){
                $area_id = $agent_info['area_id'];
                $province_id = $agent_info['province_id'];
            }else{
                $province_id = empty($_row['province_id'])?"":$_row['province_id'];
                $area_id = empty($_row['area_id'])?"":$_row['area_id'];
            }
        }else{
            $province_id = empty($_row['province_id'])?"":$_row['province_id'];
            $area_id = empty($_row['area_id'])?"":$_row['area_id'];
        }

        return array(
            "name"=>empty($_row['name'])?null:$_row['name'],
            "tel"=>empty($_row['tel'])?null:$_row['tel'],
            "charger_name"=>empty($_row['charger_name'])?null:$_row['charger_name'],
            "charger_position"=>empty($_row['charger_position'])?null:$_row['charger_position'],
            "addr"=>empty($_row['addr'])?null:$_row['addr'],
            "agent_id"=>empty($_row['agent_id'])?null:$_row['agent_id'],
            "area_id"=>empty($_row['area_id'])?null:$_row['area_id'],
            "province_id"=>empty($_row['province_id'])?null:$_row['province_id'],
            "zxs_uid"=>empty($_row['zxs_uid'])?null:$_row['zxs_uid'],
            "zxz_uid"=>empty($_row['zxz_uid'])?null:$_row['zxz_uid'],
        );
    }
    static function getSaveRowAdvance($_row){
        return array(
            "years"=>empty($_row['years'])?null:$_row['years'],
            "nums"=>empty($_row['nums'])?null:$_row['nums'],
            "type"=>empty($_row['type'])?null:$_row['type'],
            "staff_num"=>empty($_row['staff_num'])?null:$_row['staff_num'],
            "store_area"=>empty($_row['store_area'])?null:$_row['store_area'],
            "month_earn"=>empty($_row['month_earn'])?null:$_row['month_earn'],
        );
    }
    static function add($_row){
        $row = self::getSaveRow($_row);
        $row['add_time'] = Utils::date_time_now();
        $row['op_uid'] = Model_Admin_Auth::get_user_id();
        return self::_db()->insert(self::TABLE,$row);
    }
    static function remove($id){
        $row['del_time'] = Utils::date_time_now();
        $row['is_del'] = 1;
        return self::_db()->update(self::TABLE,$row,array(
            "id"=>$id
        ));
    }
    static function update_advance($id,$_row){
        $row = self::getSaveRowAdvance($_row);
        $row_a = self::row_advance($id);
        if($row_a){
            return self::_db()->update(self::TABLE_ADVANCE,$row,array("store_id"=>$id));
        }else{
            $row['store_id'] = $id;
            $row['add_time'] = Utils::date_time_now();
            $row['op_uid'] = Model_Admin_Auth::get_user_id();
            return self::_db()->insert(self::TABLE_ADVANCE,$row);
        }

    }
    static function update($id,$_row){
        $row = self::getSaveRow($_row);
        return self::_db()->update(self::TABLE,$row,array("id"=>$id));
    }
    static function row($id){
        $table = self::TABLE;
        $row = self::_db()->select_row("select * from $table where id = ?",$id);
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

        }
        return $row;
    }
    static function row_advance($id){
        $table = self::TABLE_ADVANCE;
        $row = self::_db()->select_row("select * from $table where store_id = ?",$id);
        if($row){
            $staff_info = Model_Admin_Staff::get_staff_info_by_uid_from_cache($row['op_uid']);
            $row['op_name'] = $staff_info['name'];
            $row['op_avatar'] = $staff_info['avatar'];

        }
        return $row;
    }
    static function list_rows($condition = array(),$pager = array() ,$order = array()){
        $sort_field = !in_array($order['sort_field'],
            array(
                "id",
            )
        ) ? "id":$order['sort_field'];

        $table_alias = "a";
        $sort_field = $table_alias.".".$sort_field;
        $where = "where $table_alias.is_del = 0";
        $args = array();
        $table = self::TABLE;
        if(!empty($condition['name'])){
            $where .= " and $table_alias.name like ?";
            $args[] = "%".$condition['name']."%";
        }
        if(!empty($condition['charger_name'])){
            $where .= " and $table_alias.charger_name like ?";
            $args[] = "%".$condition['charger_name']."%";
        }
        if(!empty($condition['tel'])){
            $where .= " and $table_alias.tel like ?";
            $args[] = "%".$condition['tel']."%";
        }
        if(!empty($condition['zxs_uid'])){
            $where .= " and $table_alias.zxs_uid like ?";
            $args[] = "%".$condition['zxs_uid']."%";
        }
        if(!empty($condition['kf_uid'])){
            $where .= " and $table_alias.kf_uid like ?";
            $args[] = "%".$condition['kf_uid']."%";
        }
        if(!empty($condition['area_id'])){
            $where .= " and $table_alias.area_id = ?";
            $args[] = $condition['area_id'];
        }
        if(!empty($condition['agent_id'])){
            $where .= " and $table_alias.agent_id = ?";
            $args[] = $condition['agent_id'];
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
                             $table_alias.zxz_uid = ?)";
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
        $sql = "SELECT $table_alias.*,$table_alias.id as `key`,$table_alias.id FROM $table as $table_alias  $where ORDER BY $sort_field $sort_type LIMIT $skip,$limit ";
        //_throw($sql);
        //self::_debug(array($sql,$args,$condition,$pager,$order,$sort_field,$sort_type));
        $rows = self::_db()->select_rows($sql,$args);
        foreach($rows as &$row){
            $row['key'] = $row['id'];
            if($row['agent_id']){
                $agent_info = Model_Crm_Agent::detail($row['agent_id']);

                if($agent_info && $agent_info['is_del'] == 0){
                    $row['agent_name'] = $agent_info['com_name'];
                    $row['area_id'] = $agent_info['area_id'];
                    $row['province_id'] = $agent_info['province_id'];
                }else{
                    $row['agent_name'] = "";
                    $row['area_id'] = "";
                    $row['province_id'] = "";
                }
            }else{
                $row['area_id'] = "";
                $row['province_id'] = "";
                $row['agent_name'] = "";
            }

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

        $table = self::TABLE_TRACE;
        $where = 'where 1= 1';
        $args = array();
        if(!empty($condition['store_id'])){
            $where .= " and store_id = ?";
            $args[] = $condition['store_id'];
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
        $table = self::TABLE_TRACE;
        $row = array(
            "note"         => $note,
            "ip"           => Utils::ip(),
            "store_id"     => $id,
            "op_uid"       => $op_uid,
            "add_time"     => Utils::date_time_now(),
        );
        return self::_db()->insert($table,$row);
    }
}