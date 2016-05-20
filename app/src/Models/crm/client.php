<?php
/**
 * 客户
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/2/1
 * Time: 下午5:26
 */
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
class Model_Crm_Client extends Model{
    const TABLE = "crm_client";
    const TABLE_ORDER = 'crm_client_order';
    const TABLE_PORDER = 'crm_client_porder';
    const TABLE_TRACE = 'crm_client_trace';
    const TABLE_ADVANCE = 'crm_client_advance';
    const TABLE_REVISIT = "crm_client_revisit";

    static function getSaveRow($_row){
        if(empty($_row['name'])) _throw("姓名不能为空!");
        if(empty($_row['store_id'])) _throw("所属店铺不能为空!");

        $store_info = Model_Crm_Store::detail($_row['store_id']);
        if($store_info && $store_info['is_del'] == 0){
            $_row['area_id'] = $store_info['area_id'];
            $_row['province_id'] = $store_info['province_id'];
        }
        return array(
            "name"=>$_row['name'],
            "tel"=>empty($_row['tel'])?null:$_row['tel'],
            "age"=>empty($_row['age'])?null:$_row['age'],
            "sex"=>empty($_row['tel'])?"0":1,
            "addr"=>empty($_row['addr'])?null:$_row['addr'],
            "area_id"=>empty($_row['area_id'])?null:$_row['area_id'],
            "province_id"=>empty($_row['province_id'])?null:$_row['province_id'],
            "zxs_uid"=>empty($_row['zxs_uid'])?null:$_row['zxs_uid'],
            "zxz_uid"=>empty($_row['zxz_uid'])?null:$_row['zxz_uid'],
            "store_id"=>empty($store_info)?null:$_row['store_id'],
            "sj_uid"=>empty($_row['sj_uid'])?null:$_row['sj_uid'],
            "yl_uid"=>empty($_row['yl_uid'])?null:$_row['yl_uid'],
            "kef_uid"=>empty($_row['kef_uid'])?null:$_row['kef_uid'],
            "copyer"=>empty($_row['copyer'])?null:$_row['copyer'],
        );
    }

    static function getSaveRowAdvance($_row){
        return array(
            "height"=>empty($_row['height'])?null:$_row['height'],
            "weight"=>empty($_row['weight'])?null:$_row['weight'],
            "year_earn"=>empty($_row['year_earn'])?null:$_row['year_earn'],
            "career"=>empty($_row['career'])?null:$_row['career'],
            "car_type"=>empty($_row['car_type'])?null:$_row['car_type'],
            "education_level"=>empty($_row['education_level'])?null:$_row['education_level'],
            "marital_status"=>empty($_row['marital_status'])?null:$_row['marital_status'],
            "mate_status"=>empty($_row['mate_status'])?null:$_row['mate_status'],
            "fengshui"=>empty($_row['fengshui'])?null:$_row['fengshui'],
            "dislike"=>empty($_row['dislike'])?null:$_row['dislike'],
            "character"=>empty($_row['character'])?null:$_row['character'],
            "lover"=>empty($_row['lover'])?null:$_row['lover'],
            "mate_lover"=>empty($_row['mate_lover'])?null:$_row['mate_lover'],
            "capital_type"=>empty($_row['capital_type'])?null:$_row['capital_type'],
            "consume_like"=>empty($_row['consume_like'])?null:$_row['consume_like'],
            "consume_type"=>empty($_row['consume_type'])?null:$_row['consume_type'],
            "house_type"=>empty($_row['house_type'])?null:$_row['house_type'],
            "years"=>empty($_row['years'])?null:$_row['years'],
            "max_c_p"=>empty($_row['max_c_p'])?null:$_row['max_c_p'],
            "club_y_c"=>empty($_row['club_y_c'])?null:$_row['club_y_c'],
            "out_do"=>empty($_row['out_do'])?null:$_row['out_do'],
            "has_zhen"=>empty($_row['has_zhen'])?null:$_row['has_zhen'],
            "change_party"=>empty($_row['change_party'])?null:$_row['change_party'],
            "note"=>empty($_row['note'])?null:$_row['note'],
        );
    }

    static function add($_row){
        $row = self::getSaveRow($_row);
        $row['add_time'] = Utils::date_time_now();
        $row['op_uid'] = Model_Admin_Auth::get_user_id();
        return self::_db()->insert(self::TABLE,$row);
    }

    static function update($id,$_row){
        $row = self::getSaveRow($_row);
        return self::_db()->update(self::TABLE,$row,array("id"=>$id));
    }

    static function update_advance($id,$_row){
        $row = self::getSaveRowAdvance($_row);
        self::_debug($row);
        $row_a = self::row_advance($id);
        self::_debug($row_a);

        if($row_a){
            return self::_db()->update(self::TABLE_ADVANCE,$row,array("client_id"=>$id));
        }else{
            $row['client_id'] = $id;
            $row['add_time'] = Utils::date_time_now();
            $row['op_uid'] = Model_Admin_Auth::get_user_id();
            return self::_db()->insert(self::TABLE_ADVANCE,$row);
        }

    }

    static function row($id){
        $table = self::TABLE;
        $row = self::_db()->select_row("select * from $table where id = ?",$id);
        $staff_info = Model_Admin_Staff::get_staff_info_by_uid_from_cache($row['op_uid']);
        $row['op_name'] = $staff_info['name'];
        $row['op_avatar'] = $staff_info['avatar'];

        $staff_info = Model_Admin_Staff::get_staff_info_by_uid_from_cache($row['zxs_uid']);
        $row['zxs_name'] = $staff_info['name'];
        $row['zxs_avatar'] = $staff_info['avatar'];

        $staff_info = Model_Admin_Staff::get_staff_info_by_uid_from_cache($row['zxz_uid']);
        $row['zxz_name'] = $staff_info['name'];
        $row['zxz_avatar'] = $staff_info['avatar'];

        $staff_info = Model_Admin_Staff::get_staff_info_by_uid_from_cache($row['sjs_uid']);
        $row['sjs_name'] = $staff_info['name'];
        $row['sjs_avatar'] = $staff_info['avatar'];
        $row['copyers'] = array();

        if(!empty($row['copyer'])){
            $rows = explode("|",$row['copyer']);
            foreach($rows as $_row){
                if($_row){
                    $staff_info = Model_Admin_Staff::detail_by_uid($_row);
                    $row['copyers'][] = array(
                        "key"=>$_row,
                        "name"=>$staff_info['name'],
                    );
                }
            }
        }
        return $row;
    }

    static function row_advance($id){
        $table = self::TABLE_ADVANCE;
        $row = self::_db()->select_row("select * from $table where client_id = ?",$id);
        if($row){
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
        return $row;

    }

    static function remove($id){
        $row['del_time'] = Utils::date_time_now();
        $row['is_del'] = 1;
        return self::_db()->update(self::TABLE,$row,array(
            "id"=>$id
        ));
    }

    static function list_rows($condition = array(),$pager = array() ,$order = array()){
        $sort_field = !in_array($order['sort_field'],
            array(
                "id",
                "name",
            )
        ) ? "id":$order['sort_field'];

        $table = self::TABLE;
        $table_alias = "c";
        $sort_field = $table_alias.".".$sort_field;
        $where = "where $table_alias.is_del = 0";
        $args = array();

        if(!empty($condition['name'])){
            $where .= " and $table_alias.name like ?";
            $args[] = "%".$condition['name']."%";
        }
        if(!empty($condition['tel'])){
            $where .= " and $table_alias.tel like ?";
            $args[] = "%".$condition['tel']."%";
        }
        if(!empty($condition['zxs_uid'])){
            $where .= " and $table_alias.zxs_uid like ?";
            $args[] = "%".$condition['zxs_uid']."%";
        }
        if(!empty($condition['sjs_uid'])){
            $where .= " and $table_alias.sjs_uid like ?";
            $args[] = "%".$condition['sjs_uid']."%";
        }
        if(!empty($condition['ys_uid'])){
            $where .= " and $table_alias.ys_uid like ?";
            $args[] = "%".$condition['ys_uid']."%";
        }
        if(!empty($condition['area_id'])){
            $where .= " and $table_alias.area_id = ?";
            $args[] = $condition['area_id'];
        }
        if(!empty($condition['province_id'])){
            $where .= " and $table_alias.province_id = ?";
            $args[] = $condition['province_id'];
        }
        if(!empty($condition['store_id'])){
            $where .= " and $table_alias.store_id = ?";
            $args[] = $condition['store_id'];
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
                             $table_alias.sjs_uid = ? or
                             $table_alias.yl_uid = ? or
                             $table_alias.sj_uid = ? or
                             $table_alias.kef_uid = ? or
                             $table_alias.ys_uid = ? or
                                $table_alias.copyer like ?
                             )";
            $args[] = $user_id;
            $args[] = $user_id;
            $args[] = $user_id;

            $args[] = $user_id;
            $args[] = $user_id;
            $args[] = $user_id;
            $args[] = $user_id;
            $args[] = "%|".$user_id."|%";
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
        $sql = "SELECT $table_alias.*,$table_alias.id as `key`  FROM $table as $table_alias $where ORDER BY $sort_field $sort_type LIMIT $skip,$limit ";
        //_throw($sql);
        //self::_debug(array($sql,$args,$condition,$pager,$order,$sort_field,$sort_type));
        $rows = self::_db()->select_rows($sql,$args);
        foreach($rows as &$row){
            $row['key'] = $row['id'];
            if($row['store_id']){
                $store_info = Model_Crm_Store::detail($row['store_id']);
                if($store_info && $store_info['is_del'] == 0){
                    $row['store_name'] = $store_info['name'];
                    $row['area_id'] = $store_info['area_id'];
                    $row['province_id'] = $store_info['province_id'];
                }else{
                    $row['store_name'] = "";
                    $row['area_id'] = "";
                    $row['province_id'] = "";
                }
            }else{
                $row['area_id'] = "";
                $row['province_id'] = "";
                $row['store_name'] = "";
            }

            $row['area_name'] = Model_Crm_Area::detail($row['area_id']);
            $row['province_name'] = Model_Province::detail($row['province_id']);
        }
        return array(
            "total_pages" => $total_pages,
            "total"       => $records,
            "rows"        => $rows,
        );
    }

}