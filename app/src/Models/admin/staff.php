<?php
/**
 * 员工
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/2/1
 * Time: 下午5:26
 */
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
class Model_Admin_Staff extends Model{
    const TABLE = "qjr_org_staff";
    const KEY = "qjr_st";

    static function del_cache_by_uid($user_id){
        self::_redis()->del(self::KEY.$user_id);
    }

    /**
     * 获取下属职位用户ID列表
     * @param $user_id
     * @return array
     */
    static function get_sub_position_uids($user_id){
        $staff_info = self::detail_by_uid($user_id);
        if(!empty($staff_info['position_id'])){
            $table = self::_table("org_staff");
            $table_user = self::_table("user");
            $table_position = self::_table("org_position");
            $rows = self::_db()->select_rows("select u.id from $table_user as u left join $table as s on s.mobile = u.mobile
              left join $table_position as p on p.id = s.position_id where p.superior = ?",$staff_info['position_id']);
            $res = array();
            foreach($rows as $row){
                $res[] = $row["id"];
            }
            //self::_debug($rows);
            return $res;
        }else{
            return array();
        }
    }
    static function del_cache_by_staff_id($staff_id){
        $user_id = self::get_uid_by_staff_id($staff_id);
        if($user_id) self::_redis()->del(self::KEY.$user_id);
    }

    static function detail_by_uid($user_id){
        $staff_id = self::get_staff_id_by_uid($user_id);
        return $staff_id ? self::detail($staff_id):false;
    }

    static function detail($id){
        $sql = self::get_detail_sql();
        $staff = self::_db()->select_row("$sql where s.id = ?",$id);
        return $staff;
    }
    static function table(){
        return self::_table("org_staff");
    }
    static function get_uid_by_staff_id($staff_id){
        $table = self::table();
        $table_user = self::_table("user");
        $row = self::_db()->select_row("select u.id from $table as s left join $table_user as u on u.mobile = s.mobile where s.stf_id = ?",$staff_id);
        return empty($row['id'])?null:$row['id'];
    }
    static function get_staff_id_by_uid($user_id){
        $table = self::table();
        $table_user = self::_table("user");
        $row = self::_db()->select_row("select s.stf_id from $table as s left join $table_user as u on u.mobile = s.mobile where u.user_id = ?",$user_id);
        return empty($row['id'])?null:$row['id'];
    }
    static function get_detail_sql(){
        $table = self::table();
        $user_table = self::_table("user");
        return "select
                  s.* ,s.id as `key`,s.id as staff_id,r.name as role_name,r.id as role_id,o.avatar,
                  o.openid,o.unionid,u.id as user_id,d.name as depart_name,p.name as position_name
                  from $table as s
                  left join $user_table as u on u.mobile = s.mobile
                  left join qjr_org_position as p on p.id = s.position_id
                  left join qjr_org_department as d on d.id = s.depart_id
                  left join et_user_oauth as o on o.uid = u.id
                  left join org_role as r on s.role_id = r.id";
    }
    static function get_staff_base_info_by_uid($uid){
        $staff_info = self::detail_by_uid($uid);
        if($staff_info){
            return array(
                "uid"=>$uid,
                "name"=>$staff_info['name'],
                "avatar"=>$staff_info['avatar'],
            );
        }else{
            return array(
                "uid"=>$uid,
                "name"=>"",
                "avatar"=>"",
            );
        }
    }
    static function get_staff_info_by_uid_from_cache($user_id){
        $ttl = 60;
        $staff_info = self::_redis()->get(self::KEY.$user_id);
        //self::_debug($staff_info);
        if(!$staff_info){
            //self::_debug(array(__METHOD__,$user_id,"cache unhit"));
            $staff_info = self::detail_by_uid($user_id);
            if($staff_info)
                self::_redis()->setex(self::KEY.$user_id,$ttl,json_encode($staff_info));
        }else{
            //self::_debug(array(__METHOD__,$user_id,"cache hit"));
            $staff_info = json_decode($staff_info,1);
        }
        return $staff_info;
    }
    static function getSafeRow($row){
        if($row->position_id){
            $position = self::_db()->select_row("select depart_id from qjr_org_position where id = ?",$row->position_id);
            if($position['depart_id'] != $row->depart_id){
                $row->position_id = null;
            }
        }
        $_row = array(
            "name"              => $row->name,
            "mobile"            => $row->mobile,
            "tel"               => $row->tel,
            "depart_id"         => $row->depart_id,
            "position_id"       => $row->position_id,
            "role_id"           => $row->role_id,
            "inx"               => strtolower(substr(CUtf8_PY::encode($row->name),0,1)),
        );
        return $_row;
    }
    static function add($row){
        $_row = self::getSafeRow($row);
        $id =  self::_db()->insert(self::table(),$_row);
        self::_debug(array(__METHOD__,$_row,$id));
        return $id;
    }
    static function update($id,$row){
        $_row = self::getSafeRow($row);
        self::_debug(array(__METHOD__,$_row,$id));
        self::_db()->update(self::table(),$_row,array("id"=>$id));
        self::del_cache_by_staff_id($id);
        return $id;
    }
    static function remove($id){
        self::_db()->delete(self::table(),array(
            "id"=>$id
        ));
        self::_debug(array(__METHOD__,$id));
        self::del_cache_by_staff_id($id);
    }
    static function get_staff_id_by_mobile($mobile){
        $table = self::table();
        $row = self::_db()->row("select stf_id from $table where mobile = ?",$mobile);
        if($row){
            return $row['stf_id'];
        }else{
            return null;
        }
    }
    static function bind_staff_user($stf_id,$user_id){
        $table_staff_user = self::_table("staff_user");
        self::_db()->insert($table_staff_user,array(
            "stf_id"=>$stf_id,
            "user_id"=>$user_id
        ));
    }
    static function get_auth_user_by_stf_id($staff_id){
        $table_staff_user = self::_table("staff_user");
        $table_user = self::_table("user");
        $row = self::_db()->row("select u.user_id,u.password,u.salt from $table_user as u
                left join $table_staff_user as su on su.user_id = u.user_id where su.stf_id = ?",$staff_id);
        return $row;
    }
}