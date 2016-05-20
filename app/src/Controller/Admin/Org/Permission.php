<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/1/19
 * Time: 下午2:53
 */
namespace Controller\Admin\Org;
use Controller\Admin\AbstractAdmin as AbstractAdmin;
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
use Model_Admin_Role;
class Permission extends AbstractAdmin{
    function action_save($role_id,$permissions){
        Model_Admin_Role::update_permission($role_id,$permissions);
        return array("msg"=>"保存权限成功");
    }
    function action_get($role_id){
        $permissions = Model_Admin_Role::get_permission($role_id);
        $system_permissions = file_get_contents(PATH_APP."/config/json/permission.json");
        return array(
            "permissions"=>$permissions,
            "system_permissions"=>json_decode($system_permissions)
        );
    }
}
