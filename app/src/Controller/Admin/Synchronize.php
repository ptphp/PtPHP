<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/1/19
 * Time: 下午5:35
 */
namespace Controller\Admin;
use Model_Admin_Auth;

Class Synchronize extends AbstractAdmin{
    function action_data(){
        $user_id = Model_Admin_Auth::get_user_id();
        return array(
            "timestamp"=>time(),
            "permissions"=>array(),
            "staff"=>array(),
        );
    }
}