<?php
/**
 * @link http://www.ptphp.com
 * @copyright Copyright (c) 2012 PtPHP Software LLC
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author joseph <ptphp@qq.com>
 */

namespace PtPHP;

use Memcached;

class PtMemcache{
    static function obj($key = "default"){
        $g_key = "memcache_obj_$key";
        if(!isset($GLOBALS[$g_key])){
            global $setting;
            if(isset($setting) && isset($setting['mem_cache']) && isset($setting['mem_cache'][$key])){
                $config = $setting['mem_cache'][$key];
                $host = $config['host'];
                $port = $config['port'];
            }else{
                $host = "127.0.0.1";
                $port = 11211;
            }
            $memcache_obj =  new Memcached;
            $memcache_obj->addServer($host, $port);
            $memcache_obj->setOption(Memcached::OPT_BINARY_PROTOCOL, true);

            $GLOBALS[$g_key] = $memcache_obj;
        }else{
            $memcache_obj = $GLOBALS[$g_key];
        }
        return $memcache_obj;
    }
}