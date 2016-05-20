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
    const AUTH_LOGIN_SEESION_KEY = "admin_user_id";

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
    static function check_login($username,$password){
        $res = false;
        if(Utils::is_mobile($username)){
            $stf_id = Model_Admin_Staff::get_staff_id_by_mobile($username);
            if(!$stf_id) _throw("员工不存在");
            self::_debug(array(__METHOD__,$stf_id));
            $user = Model_Admin_Staff::get_auth_user_by_stf_id($stf_id);
            self::_debug(array("auth user",$stf_id,$user));
            if(!$user) _throw("员工未授权");
            $_password = $user['password'];
            $salt = $user['salt'];
            if($_password !== self::gen_password($password,$salt)) _throw("密码不正确");
            $res = true;
        }
        return $res;
    }
    static function gen_salt(){
        return md5(time().rand(10000,99999));
    }
    static function gen_password($password,$salt){
        $password = md5(md5($password).$salt);
        self::_debug(array(__METHOD__,$password,$salt));
        return $password;
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