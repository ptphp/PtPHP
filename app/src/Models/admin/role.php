<?php
/**
 * 角色
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/2/1
 * Time: 下午5:26
 */
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
class Model_Admin_Role extends Model{

    static function update_permission($role_id,$permissions){
        if(empty($role_id)) _throw("role_id 不能为空");

        if(is_array($permissions)){
            $permissions = json_encode($permissions);
        }
        $permissions = Utils::unicodeString($permissions);

        $table = self::_table("role_perm");
        $row = self::_db()->select_row("select * from $table where role_id = ?",$role_id);
        if($row){
            $role_id = $row['role_id'];
            self::_db()->update($table,array(
                "perm"=>$permissions
            ),array("role_id"=>$role_id));
        }else{
            $id = self::_db()->insert($table,array(
                "perm"=>$permissions,
                "role_id"=>$role_id
            ));
        }
        return $role_id;
    }
    static function get_permission($role_id){
        $permissions = array();
        $permissions['工作台'] = true;
        $permissions['工作台/个人中心'] = true;
        if(empty($role_id)) return $permissions;
        $table = self::_table("role_perm");
        $row = self::_db()->select_row("select perm from $table where role_id = ?",$role_id);
        if($row && $row['perm']){
            $permissions = json_decode($row['perm'],1);
        }
        return $permissions;
    }
}