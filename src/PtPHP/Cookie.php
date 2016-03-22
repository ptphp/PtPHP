<?php
/**
 * @link http://www.ptphp.com
 * @copyright Copyright (c) 2012 PtPHP Software LLC
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author joseph <ptphp@qq.com>
 */

namespace PtPHP;

class Cookie {
    static function set($name,$value,$expire = 24){
        if(is_array($value)){
            $value = json_encode($value);
        }
        setcookie($name,$value,time()+3600*$expire,"/");
    }
    static function get($name,$default = null){
        if(isset($_COOKIE[$name])){
            return $_COOKIE[$name];
        }else{
            return $default;
        }
    }
    static function del($key){
        if(isset($_COOKIE[$key])){
            setcookie($key,"",time()-3600,"/");
        }
    }

    static function clear_all(){
        foreach($_COOKIE as $key => $c){
            self::del($key);
        }
    }
    static function encode($name,$value){
        global $setting;
        if(empty($setting['cookie_secret'])){
            $key = md5(__FILE__);
        }else{
            $key = $setting['cookie_secret'];
        }
        return Crypt::create_signed_value($key,$name,$value);
    }
    static function decode($name,$value,$max_age_days = 31){
        global $setting;
        if(empty($setting['cookie_secret'])){
            $key = md5(__FILE__);
        }else{
            $key = $setting['cookie_secret'];
        }
        return Crypt::decode_signed_value($key,$name,$value,$max_age_days);
    }
}
