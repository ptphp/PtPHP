<?php
/**
 * @link http://www.ptphp.com
 * @copyright Copyright (c) 2012 PtPHP Software LLC
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author joseph <ptphp@qq.com>
 */

namespace PtPHP;

use Exception;
use RedisException;
use Redis;

class PtRedis{
    static $config = array(
        "default"=>array(
            "host"=>"127.0.0.1",
            "port"=>6379,
            "pconnect"=>false,
            "timeout"=>1.5,
            "auth"=>"",
        )
    );
    static function obj($key = 'default'){
        $g_key = "redis_cache_obj_$key";
        $default_timeout = 2.5;
        if(!isset($GLOBALS[$g_key])){
            if(isset(self::$config[$key])){
                $_config = self::$config[$key];
                $config['host']     = isset($_config['host'])?$_config['host']:"127.0.0.1";
                $config['port']     = isset($_config['port'])?$_config['port']:6379;
                $config['pconnect'] = isset($_config['pconnect'])?$_config['pconnect']:false;
                $config['timeout']  = isset($_config['timeout'])?$_config['timeout']:$default_timeout;
                $config['auth']     = isset($_config['auth'])?$_config['auth']:"";
            }else{
                $config['host']     = "127.0.0.1";
                $config['port']     = "6379";
                $config['pconnect'] = false;
                $config['timeout']  = $default_timeout;
                $config['timeout']  = "";
            }

            if(!class_exists("Redis")){
                throw new Exception("Redis not found");
            }
            try{
                $timeout = $config['timeout'];
                $obj  = new Redis();
                if(isset($config['pconnect']) && $config['pconnect']){
                    $obj->pconnect($config['host'],intval($config['port']),$timeout);
                }else{
                    $obj->connect($config['host'],intval($config['port']),$timeout);
                }
                if($config['auth'])
                    $obj->auth($config['auth']);
                $GLOBALS[$g_key] = $obj;

            }catch (RedisException $e){
                throw new Exception($e->getMessage());
            }catch (Exception $e){
                throw new Exception($e->getMessage());
            }

        }else{
            $obj = $GLOBALS[$g_key];
        }
        return $obj;
    }
}