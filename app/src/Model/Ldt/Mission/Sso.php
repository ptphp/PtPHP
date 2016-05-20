<?php
namespace Model\Ldt\Mission;
use PtPHP\Model as Model;
use PtPHP\Curl as Curl;

class Sso extends Model{
    static function handleResponse($user_info){
        $user_id = $user_info['user_id'];
        return $user_id;
    }
    static function getUserInfo($access_token){
        $curl = new Curl();
        $url = SSO_USER_INFO_URL."&access_token=".$access_token;
        $res = $curl->get($url);
        $body = json_decode($res['body'],1);
        $user_info = null;
        if($body['error'] == 0){
            $user_info = $body['result'];
            //self::_debug($user_info);
        }
        return $user_info;
    }
}