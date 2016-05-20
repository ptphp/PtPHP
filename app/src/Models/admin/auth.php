<?php
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
use PtPHP\Safe as Safe;

/**
 * Auth
 * Class Model_Auth
 * @author Joseph
 */
class Model_Admin_Auth extends Model{
    const AUTH_LOGIN_SEESION_KEY = "is_safe_logined";

    static function set_login_session($user_id){
        Model_Session::session_start();
        //self::_debug(array("set_login",__METHOD__,session_id(),$user_id));
        $_SESSION[self::AUTH_LOGIN_SEESION_KEY]  = $user_id;
        $_SESSION['safe_logined'] = 1;
    }
    static function get_user_id(){
        Model_Session::session_start();
        return empty($_SESSION[self::AUTH_LOGIN_SEESION_KEY]) ? null : $_SESSION[self::AUTH_LOGIN_SEESION_KEY];
    }

    static function get_role_id(){
        $user_id = self::get_user_id();
        $staff_info = Model_Admin_Staff::detail_by_uid($user_id);
        return ($staff_info && $staff_info['role_id']) ? $staff_info['role_id']:0;
    }

    static function logout(){
        Model_Session::session_start(true);
        session_destroy();
        unset($_SESSION[self::AUTH_LOGIN_SEESION_KEY]);
        unset($_SESSION['safe_logined']);
    }
}