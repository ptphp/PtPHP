<?php
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
use PtPHP\Crypt as Crypt;


/**
 * Hash
 * Class Model_Hash
 * @author Joseph
 */
class Model_Hash extends Model{
    const TABLE                  = "sys_hash";
    const CACHE_KEY              = "s_has_3";
    static function update_hash($hash,$key,$value,$title = null){
        $row = array(
            "value"=>$value,
        );

        if($title)
            $row['title'] = $title;
        $table = self::TABLE;
        $res = self::_db()->select_row("select `value` from $table where `hash` = ? and `key` = ?",$hash,$key);
        if($res){
            self::_db()->update(self::TABLE,$row,array(
                "hash"=>$hash,
                "key"=>$key
            ));
            self::cache_hash($hash,$key,$value);
        }else{
            self::add_hash($hash,$key,$value,$title);
        }
    }
    static function add_hash($hash,$key,$value,$title){
        self::_db()->insert(self::TABLE,array(
            "hash"=>$hash,
            "key"=>$key,
            "value"=>$value,
            "title"=>$title,
        ));
        self::cache_hash($hash,$key,$value);

    }
    static function get_hash($hash,$key){
        $value =  self::_redis()->hGet(self::CACHE_KEY.$hash,$key);
        if(!$value){
            $table = self::TABLE;
            $res = self::_db()->select_row("select `value` from $table where `hash` = ? and `key` = ?",$hash,$key);
            if($res){
                $value = $res['value'];
            }
        }
        return $value;
    }
    static function cache_hash($hash,$key,$value){
        self::_redis()->hSet(self::CACHE_KEY.$hash,$key,$value);
    }

}