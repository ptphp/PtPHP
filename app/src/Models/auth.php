<?php
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
use PtPHP\Safe as Safe;

/**
 * Auth
 * Class Model_Auth
 * @author Joseph
 */
class Model_Auth extends Model{
    const ENCRYPT_EXPIRE_TIME = 7200; #60*60*2
    const ENCRYPT_FIELD_NAME = "_encrypt_code";
    const ENCRYPT_CACEH_KEY = "encrypt_";
    const AUTH_LOGIN_SEESION_KEY = "is_logined";

    static function set_login_session($user_id){
        Model_Session::session_start(true);
        self::_debug(array("set_login",__METHOD__,session_id(),$user_id));
        $_SESSION[self::AUTH_LOGIN_SEESION_KEY]  = $user_id;
    }
    static function logout(){
        Model_Session::session_start(true);
        unset($_SESSION[self::AUTH_LOGIN_SEESION_KEY]);
    }

    static function get_user_id(){
        Model_Session::session_start(true);
        return empty($_SESSION[self::AUTH_LOGIN_SEESION_KEY]) ? null : $_SESSION[self::AUTH_LOGIN_SEESION_KEY];
    }
    static function is_logined(){
        Model_Session::session_start(true);
        $uid = self::get_user_id();
        return empty($uid) ? false : true;
    }
    static function do_login($username,$passowrd){
        if(strlen($passowrd) < 6) _throw("密码少于6位");
        $table = self::_table("user");
        $row = self::_db()->select_row("select id,password from $table where mobile = ?",$username);
        if(!$row) _throw("用户不存在");
        $passowrd = md5(sha1($passowrd));
        if($row['password'] != $passowrd) _throw("密码不正确");
        $_SESSION['safe_logined'] = 1;
        Model_Admin_Auth::set_login_session($row['id']);
    }
    static function login_safe($username,$password,$safe_token){
        if(!$safe_token && PtConfig::$userRsaAuth) _throw("safe_token is null");
        if(!$username) _throw("username is null");
        if(!$password) _throw("password is null");
        if(PtConfig::$userRsaAuth){
            $encrypt_data = self::_redis()->get(self::ENCRYPT_CACEH_KEY . $safe_token);
            if(empty($encrypt_data)) _throw("加密信息已过期");
            $encrypt_data = json_decode($encrypt_data);
            $private_key = $encrypt_data->private_key;
            $req = array(
                'username' => $username,
                'password' => $password
            );
            $req = Safe::decrypt($req,$private_key);
            //self::_debug($req);
            if(!$req) _throw("解密失败");
            $username = $req['username'];
            $password = $req['password'];
        }
        if(self::__check_safe_login($username,$password)){
            Model_Admin_Auth::set_login_session(-1);
        } elseif($user_id = self::__check_admin_login($username,$password)){
            Model_Admin_Auth::set_login_session($user_id);
        }else{
            _throw("用户和密码不正确");
        }
        return true;
    }
    static function __check_admin_login($username,$password){
        $res = Model_Admin_Auth::check_login($username,$password);
        return $res;
    }
    static function __check_safe_login($username,$password){
        $res = (
            property_exists("PtConfig","safeLogin") &&
            !empty(PtConfig::$safeLogin['username']) &&
            !empty(PtConfig::$safeLogin['password']) &&
            $username === PtConfig::$safeLogin['username'] &&
            $password === PtConfig::$safeLogin['password']
        );
        return $res;
    }

    static function gen_token($salt){
        return md5(uniqid() . mt_rand() . microtime(1) . $salt);
    }
    static function cache_encrypt_info($encrypt,$ttl){
        $token = self::gen_token(__FILE__.__METHOD__.__LINE__);
        self::_redis()->setex(
            self::ENCRYPT_CACEH_KEY . $token,
            self::ENCRYPT_EXPIRE_TIME,
            json_encode($encrypt)
        );
        return $token;
    }
    static function encrypt_data(){
        Model_Session::session_start(true);
        $encrypt = Safe::create_encrypt_info();
        $token = self::cache_encrypt_info($encrypt, self::ENCRYPT_EXPIRE_TIME);
        $encryptData = array(
            'field_name' => self::ENCRYPT_FIELD_NAME,
            'field_value' => $token,
            'public_key' => $encrypt['public_key'],
        );
        return $encryptData;
    }

}