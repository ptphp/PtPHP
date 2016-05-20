<?php
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
/**
 * Class Model_Wechat_Api
 * @author Joseph
 */

class Model_Wechat_Api extends Model{
    const ACCESS_TOKEN_CACHE_KEY = "wx_access_token";
    static $config = array(
        "appid"=>"",
        "appsecret"=>"",
    );
    static $config_open = array(
        "appid"=>"",
        "appsecret"=>"",
    );
    static $arrContextOptions = array(
        "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
        ),
    );

    static function get_appid(){
        if(Utils::is_wechat_browser()){
            $appid = self::$config['appid'];
        }else{
            $appid = self::$config_open['appid'];
        }

        if(empty($appid)) _throw("appid not found");
        return $appid;
    }
    static function get_appsecret(){
        if(Utils::is_wechat_browser()){
            $appsecret = self::$config['appsecret'];
        }else{
            $appsecret = self::$config_open['appsecret'];
        }
        if(empty($appsecret)) _throw("appsecret not found");
        return $appsecret;
    }
    /**
     * 当前网址
     * @return string
     */
    static function _get_redirect_uri(){
        $url = Utils::current_url_address();
        self::_debug(array("current_url_address",$url));
        return $url;
    }
    static function get_auth_code_url(){
        if(!Utils::is_wechat_browser()){
            $wechat_login_url = self::_get_auth_code_url_from_web();
            self::_debug(array("code url from open",$wechat_login_url));
        }else{
            $wechat_login_url = self::_get_auth_code_url_from_wechat();
            self::_debug(array("code url from wechat",$wechat_login_url));
        }
        return $wechat_login_url;
    }
    static function get_auth_code(){
        if(!isset($_GET['code'])){
            $auth_url = self::get_auth_code_url();
            header("Location: ".$auth_url);exit;
        }else{
            self::_debug(array("auth code",$_GET['code']));
            return $_GET['code'];
        }
    }


    static function _get_auth_code_url_from_web($state = "state"){
        $appid = self::get_appid();
        $redirect_uri = self::_get_redirect_uri();
        $url = 'https://open.weixin.qq.com/connect/qrconnect?appid=' . $appid .
            '&redirect_uri=' . urlencode($redirect_uri) .
            '&response_type=code&scope=snsapi_login&state='.$state.'#wechat_redirect';
        return $url;
    }

    /**
     * 微信浏览器中网页授权获取用户基本信息 URL
     *
     * @param string $scope   snsapi_base     （不弹出授权页面，直接跳转，只能获取用户openid）;
     *                        snsapi_userinfo （弹出授权页面，可通过openid拿到昵称、性别、所在地
     * @param string $scope   重定向后会带上state参数，开发者可以填写a-zA-Z0-9的参数值
     * @return string
     */

    static function _get_auth_code_url_from_wechat($scope = "snsapi_userinfo",$state = "state")
    {
        $appid = self::get_appid();
        #用户指定跳转网址
        $redirect_uri = self::_get_redirect_uri();
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.
            '&redirect_uri='.urlencode($redirect_uri).
            '&response_type=code&scope='.$scope.
            '&state='.$state.'#wechat_redirect';
        return $url;
    }

    static function get_access_token(){
        Model_Session::session_start();
        $access_token = empty($_SESSION[self::ACCESS_TOKEN_CACHE_KEY])?null:$_SESSION[self::ACCESS_TOKEN_CACHE_KEY];
        if(!empty($access_token)){
            $token = json_decode($access_token,1);
            self::_debug(array($token['expires'],time()));
            if($token['expires'] < time()){
                self::_debug(array("token from session cache expire",$token));
                $res = self::get_access_token_from_wechat();
            }else{
                self::_debug(array("token from session cache",$token));
                $res = array(
                    'access_token'=>$token['access_token'],
                    'openid'=>$token['openid'],
                    'unionid'=>empty($token['unionid']) ? "":$token['unionid'],
                );
            }
        }else{
            self::_debug("token from wechat");
            $res = self::get_access_token_from_wechat();
        }
        return $res;
    }
    static function get_access_token_from_wechat(){
        $code = self::get_auth_code();
        $appid = self::get_appid();
        $secret = self::get_appsecret();
        $token_url = self::_get_access_token_url($appid,$secret,$code);
        self::_debug(array("access_token url:",$token_url));
        $token_str = self::_fetch_access_token($token_url);
        if(empty($token_str)){
            self::_error("获取 token 失败,返回:null");
            _throw("获取 token 失败");
        }
        $token = json_decode($token_str,1);
        if(!empty($token['errcode'])){
            self::_error("获取 token 失败,result:".$token_str);
            _throw(json_encode($token['errmsg']));
        }
        self::_debug(array("get_access_token_from_wechat => access_token",$token));
        Model_Session::session_start();
        $token["expires"] = time() + $token["expires_in"];
        $_SESSION[self::ACCESS_TOKEN_CACHE_KEY] = json_encode($token);
        return array(
            'access_token'=> $token['access_token'],
            'openid'      => $token['openid'],
            'unionid'     => empty($token['unionid']) ? null : $token['unionid'],
        );
    }

    static function _get_access_token_url($appid,$secret,$code){
        return "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid.
        "&secret=".$secret.
        "&code=".$code.
        "&grant_type=authorization_code";
    }
    static function _fetch_access_token($url){
        $token = file_get_contents($url,false,stream_context_create(self::$arrContextOptions));
        return $token;
    }
    static function _get_user_info_url($access_token,$openid){
        return "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=". $openid;
    }
    /**
     * @return mixed|string
     * @throws Exception
     */
    static function get_auth_info(){
        $token = self::get_access_token();
        $access_token = $token['access_token'];
        $openid       = $token['openid'];
        $url = self::_get_user_info_url($access_token,$openid);
        $user_info = file_get_contents($url,false,stream_context_create(self::$arrContextOptions));
        if(empty($user_info)){
            _throw("获取用户信息失败,return:null");
            self::_error("获取用户信息失败,return:null");
        }
        $user_info = json_decode($user_info,true);
        self::_debug(array(__METHOD__,$user_info));
        return $user_info;
    }
}
