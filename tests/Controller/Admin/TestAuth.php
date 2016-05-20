<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/1/19
 * Time: 下午2:53
 */
namespace Controller\Admin;
use PtPHP\UnitTest as UnitTest;

class TestAuth extends UnitTest{

    function test_action_login(){
        $auth = new Auth();
        $_SESSION = array();
        \PtConfig::$userRsaAuth = false;
        $auth_info = $auth->action_info();
        $res = $auth->action_login("13555555555","111111");
        //var_export($res);
        $user_id = \Model_Admin_Auth::get_user_id();
        var_export($user_id);
    }
    function test_gen_password(){
        //afdfc8aa0e94cc2d65bd82a841d579dc
        echo $password = \Model_Admin_Auth::gen_password("111111","0301d9ff0daaee5ac3b0f6dc755efa63");
    }
}