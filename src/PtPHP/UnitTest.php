<?php
/**
 * @link http://www.ptphp.com
 * @copyright Copyright (c) 2012 PtPHP Software LLC
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author joseph <ptphp@qq.com>
 */

namespace PtPHP;

use PHPUnit_Framework_TestCase;

class UnitTest extends PHPUnit_Framework_TestCase{
    var $curl_response = array();
    var $http_opt = array();
    var $test_host = '';
    function log($msg){
        echo($msg);
    }
    function clear_cookie($cookie_file){
        if(is_file($cookie_file)) unlink($cookie_file);
    }
    function __construct(){
        parent::__construct();
    }
    static function _debug($msg = ''){
        $argc = func_num_args();
        if($argc > 1){
            $msg = func_get_args();
        }elseif($argc == 1){
            $msg = func_get_arg(0);
        }
        Logger::debug($msg);
    }
    static function _info($msg = ''){
        $argc = func_num_args();
        if($argc > 1){
            $msg = func_get_args();
        }elseif($argc == 1){
            $msg = func_get_arg(0);
        }
        Logger::info($msg);
    }
    static function _warn($msg = ''){
        $argc = func_num_args();
        if($argc > 1){
            $msg = func_get_args();
        }elseif($argc == 1){
            $msg = func_get_arg(0);
        }
        Logger::warn($msg);
    }

    static function cli($cli,$action,$args = ""){
        $cmd = "php ".PATH_APP."/bin/ptphp.php --cli=$cli --action=$action ".$args;
        Logger::trace($cmd);
        $res = shell_exec($cmd);
        Logger::trace($res);
        return $res;
    }
    function __parse_url_action($action){
        $this->curl_response = array();
        if(substr($action,0,7) != "http://"){
            if(substr($action,0,1) != "/"){
                $action = "/".$action;
            }
            if($this->test_host == ''){
                $action = TEST_URL.$action;
            }else{
                $action = "http://".str_replace("http://","",$this->test_host).$action;
            }

        }
        //$this->log($action);
        return $action;
    }

    function http_request($method,$url,$options){
        $curl = new Curl();
        $_options = array();
        $data = "";
        if(isset($options["data"])){
            if(is_array($options["data"]) || is_object($options["data"])){
                if(!empty($options['setting']["post_json"])){
                    $data = json_encode($options["data"]);
                }else{
                    $data = http_build_query($options['data']);
                }
            }else{
                $data = $options["data"];
            }
        }

        if(!empty($options['setting']["debug"])){
            $_options[CURLOPT_VERBOSE] = 1;
        }
        if(!empty($options['setting']["cookie_file"])){
            $_options[CURLOPT_COOKIEFILE] = $options['setting']["cookie_file"];
        }
        if(!empty($options['setting']["local_proxy"])){
            $_options[CURLOPT_HTTPPROXYTUNNEL] = 1;
            if($options['setting']["local_proxy"] == 1){
                $_options[CURLOPT_PROXY] = "127.0.0.1:80";
            }else{
                $_options[CURLOPT_PROXY] = $options['setting']["local_proxy"];
            }
        }

        $url = $this->__parse_url_action($url);
        $res = $curl->request(strtoupper($method),$url,$data,$_options);

        if(empty($options['setting']["debug"])){
            if(!empty($options['setting']["print_response"])){
                echo "\nHTTP Response\n";
                print_r($res);
                echo "\n";
            }else{
                if(!empty($options['setting']["print_response_header"])){
                    echo "\nHTTP Response Header:\n";
                    echo($res['header']);
                    echo "\n";
                }
                if(!empty($options['setting']["print_response_cookie"])){
                    echo "\nHTTP Response Cookie: in ==> ".$res['cookie_file']."\n";
                    print_r($res['cookie']);
                    echo "\n";
                }
                if(!empty($options['setting']["print_response_info"])){
                    echo "\nHTTP Response Info: in ==> ".$res['cookie_file']."\n";
                    print_r($res['info']);
                    echo "\n";
                }
            }
        }
        return $res['body'];
    }
}
