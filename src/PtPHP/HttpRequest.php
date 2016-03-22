<?php
namespace PtPHP;
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 7/17/15
 * Time: 12:33 PM
 */
class HttpRequest {
    static function is_xhr(){
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
    }
    /**
     * 判断是不是微信浏览器
     * @return bool
     */
    static function is_wx_browser(){
        return isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false;
    }
    static function client(){
        if(!isset($GLOBALS['http_client_obj'])){
            $http_client_obj =  new Curl();
            $GLOBALS['http_client_obj'] = $http_client_obj;
        }else{
            $http_client_obj = $GLOBALS['http_client_obj'];
        }
        return $http_client_obj;
    }
    static function param(){
        $_args = array();
        $args = func_get_args();
        foreach($args as $key){
            $_args[$key] = isset($_REQUEST[$key])?trim($_REQUEST[$key]):null;
        }
        return $_args;
    }
    static function param_get(){
        $_args = array();
        $args = func_get_args();
        foreach($args as $key){
            $_args[$key] = isset($_GET[$key])?$_GET[$key]:null;
        }
        return $_args;
    }
    static function param_post(){
        $_args = array();
        $args = func_get_args();
        foreach($args as $key){
            $_args[$key] = isset($_POST[$key])?$_POST[$key]:null;
        }
        return $_args;
    }
    static function param_body(){
        return @file_get_contents('php://input');
    }
}
