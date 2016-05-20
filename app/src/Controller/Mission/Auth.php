<?php
namespace Controller\Mission;
use PtPHP\Model as Model;
use Model_Session;
class Auth extends Model{
    const AUTH_UID_KEY = "is_logined";
    static function get_user_id(){
        Model_Session::session_start();
        return empty($_SESSION[self::AUTH_UID_KEY]) ? null : $_SESSION[self::AUTH_UID_KEY];
    }
    static function set_auth_uid($uid){
        Model_Session::session_start();
        $_SESSION[self::AUTH_UID_KEY] = $uid;
    }
}