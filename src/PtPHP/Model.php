<?php
/**
 * @link http://www.ptphp.com
 * @copyright Copyright (c) 2012 PtPHP Software LLC
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author joseph <ptphp@qq.com>
 */

namespace PtPHP;

class Model{
    static function _db($key = "default"){
        return Database::init($key);
    }
    static function cli($cli,$action,$args = ""){
        $cmd = "php ".PATH_APP."/bin/ptphp.php --cli=$cli --action=$action ".$args;
        Logger::trace($cmd);
        $res = shell_exec($cmd);
        Logger::trace($res);
        return $res;
    }
    static function _redis($key = "default"){
        return PtRedis::obj($key);
    }
    static function _debug($msg = ''){
        //Logger::print_config();
        $argc = func_num_args();
        if($argc > 1){
            $msg = func_get_args();
        }elseif($argc == 1){
            $msg = func_get_arg(0);
        }
        Logger::debug($msg);
    }
    static function _error($msg = ''){
        //Logger::print_config();
        $argc = func_num_args();
        if($argc > 1){
            $msg = func_get_args();
        }elseif($argc == 1){
            $msg = func_get_arg(0);
        }
        Logger::error($msg);
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
}