<?php
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;

/**
 * Session
 * Class Model_Session
 * @author Joseph
 */
class Model_Session extends Model{
    static function session_id(){
        self::session_start();
        return session_id();
    }
    static function gen_access_token(){
        return md5(uniqid() . mt_rand() . microtime(1)).__FILE__;
    }
    static function gen_valid_access_token($token){
        return md5(Utils::server_param('HTTP_USER_AGENT'    ).$token);
    }
    static function session_start(){
        if(Utils::is_cli()) return;
        static $started = false;
        if(!$started){
            session_start();
            $started = true;
        }
    }
}