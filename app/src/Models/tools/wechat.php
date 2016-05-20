<?php
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;

class Model_Tools_Wechat extends Model{
    static function get_js_sign_pack(){
        require_once PATH_LIBS."/wechat/jssdk.php";
        $jssdk = new JSSDK(Model_Setting::get("WX_APPID"), Model_Setting::get("WX_APPSECRET"));
        $signPackage = $jssdk->GetSignPackage();
        return $signPackage;
    }
    static function media_get($media_id){
        require_once PATH_LIBS."/wechat/jssdk.php";
        $jssdk = new JSSDK(Model_Setting::get("WX_APPID"), Model_Setting::get("WX_APPSECRET"));
        $url = $jssdk->media_get($media_id);
        return $url;
    }
    function action_test_get_js_sign_pack(){
        $res = self::get_js_sign_pack();
        var_dump($res);
    }
    function action_get_js_sign_pack(){
        return self::get_js_sign_pack();
    }
}