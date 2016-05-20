<?php
/**
 * 权限
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/2/1
 * Time: 下午5:26
 */
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
class Model_Admin_Permission extends Model{
    const TABLE = "org_permission";
    const KEY = "qjr_per";

    static function get_all_permission_items(){
        $items = file_get_contents(PATH_APP."/permission.json");
        $items = json_decode($items,1);
        return $items;
    }
    static function check($node){
        $role_id = Model_Admin_Auth::get_role_id();
        return self::_check($role_id,$node);
    }
    static function _check($role_id,$node){
        $permission = Model_Admin_Role::get_permission($role_id);
        if(empty($permission)) return false;
        return !empty($permission[$node]);
    }
}