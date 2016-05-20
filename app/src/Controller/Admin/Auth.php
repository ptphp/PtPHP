<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/1/19
 * Time: 下午2:53
 */
namespace Controller\Admin;

use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
use Model_Session;
use Model_Auth;
use Model_Admin_Auth;
use PtConfig;
class Auth extends Model{
    function __construct()
    {
        Model_Session::session_start(true);
    }

    /**
     * 登陆
     * @return string
     */
    function action_login($username,$password){
        $safe_token = Utils::I(Model_Auth::ENCRYPT_FIELD_NAME);
        Model_Auth::login_safe($username,$password,$safe_token);
        $redirect = null;
        return array(
            "message"=>"登陆成功",
            "redirect"=>$redirect,
        );
    }
    /**
     * 登陆
     * @return string
     */
    function action_login_local(){
        if(!Utils::is_local_dev()) _throw("no auth");
        $username = Utils::I("username");
        $password = Utils::I("password");
        Model_Auth::do_login($username,$password);
        return "登陆成功";
    }

    /**
     * 安全登陆生成RSA信息
     * @return array
     */
    function action_info(){
        Model_Session::session_start();
        self::_debug(array(__METHOD__,$_SESSION));
        //unset($_SESSION['wx_auth_info']);
        if(PtConfig::$userRsaAuth){
            $encryptData = Model_Auth::encrypt_data();
            unset($encryptData['private_key']);
        }else{
            $encryptData = array();
        }
        return array(
            "encryptData"=>$encryptData,
            "useRsaAuth"=>PtConfig::$userRsaAuth,
            "user_id"=>Model_Admin_Auth::get_user_id(),
            "wx_auth_info"=>empty($_SESSION['wx_auth_info'])?null:$_SESSION['wx_auth_info']
        );
    }

    function action_logout(){
        Model_Admin_Auth::logout();
    }
    /**
     * 微信绑定手机号
     * @return string
     */
    function action_wechat_bind_mobile(){
        Model_Session::session_start(true);
        $mobile    = Utils::I("mobile");
        $nick_name = Utils::I("nick_name");
        $captcha   = Utils::I("captcha");
        $oauth_id  = Utils::I("oauth_id");
        self::_debug(array($oauth_id));
        $safe_token = Utils::I(Model_Auth::ENCRYPT_FIELD_NAME);
        if(!$safe_token) _throw("safe_token is null");
        //Model_Auth::login_safe($username,$password,$safe_token);
        $encrypt_data = self::_redis()->get(Model_Auth::ENCRYPT_CACEH_KEY . $safe_token);
        if(empty($encrypt_data)) _throw("加密信息已过期");
        $encrypt_data = json_decode($encrypt_data);
        $private_key = $encrypt_data->private_key;
        $reqData = array(
            'mobile'    => $mobile,
            'captcha'   => $captcha,
            'nick_name' => $nick_name,
        );
        $reqData = Safe::decrypt($reqData,$private_key);
        self::_debug($reqData);
        if(!$reqData) _throw("解密失败");
        $mobile    = $reqData['mobile'];
        $nick_name = $reqData['nick_name'];
        $captcha   = $reqData['captcha'];
        if(!Utils::is_mobile($mobile)) _throw("手机号不合法");

        $key = Controller_Captcha::get_captcha_session_key($mobile,"wechat_bind_mobile");
        self::_debug($key);
        if(empty($_SESSION[$key])) _throw("验证码已过期");
        $_captcha_session = $_SESSION[$key];
        self::_debug($_captcha_session);
        list($_captcha,$time) = explode("|",$_captcha_session);
        if(time() - $time > 60*60*5){
            unset($_SESSION[$key]);
            _throw("验证码已过期");
        }
        self::_debug($_captcha_session);
        if($captcha != $_captcha) _throw("验证码不正确");
        if(! $user_id = Model_User::check_user_exsits($mobile)){
            $user = array(
                "password"=>null,
                "mobile"=>$mobile,
                "nick_name"=>$nick_name,
                "add_time"=>Utils::date_time_now(),
                "email"=>null,
            );
            self::_debug("create user");
            self::_debug($user);
            $user_id = self::_db()->insert(Model_User::TABLE,$user);
        }
        self::_debug(array("update",Model_Wechat_User::TABLE,$user_id,$oauth_id));
        self::_db()->update(Model_Wechat_User::TABLE,array(
            "uid"=>$user_id
        ),array(
            "id"=>$oauth_id
        ));
        $wx_auth_info = $_SESSION['wx_auth_info'];
        $wx_auth_info['uid'] = $user_id;
        $_SESSION['wx_auth_info'] = $wx_auth_info;
        self::_debug($user_id);
        Model_Admin_Auth::set_login_session($user_id);
        //set login
        unset($_SESSION[$key]);
        Controller_Captcha::clear_status_key("wechat_bind_mobile");
        $redirect = self::get_redirect_url();
        return array(
            "message"=>"绑定成功",
            "redirect"=>$redirect,
        );
    }
    static function get_redirect_url(){
        $redirect = "/";
        if(isset($_SESSION['module'])){
            if($_SESSION['module'] == "oa"){
                $redirect = "/admin/weixin/";
            }
            if($_SESSION['module'] == "crm"){
                $redirect = "/admin/crm/";
            }
        }
        return $redirect;
    }
}