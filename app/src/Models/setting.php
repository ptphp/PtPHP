<?php
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
use PtPHP\Crypt as Crypt;


/**
 * Setting
 * Class Model_Setting
 * @author Joseph
 */
class Model_Setting extends Model{
    const CACHE_KEY              = "sys_setting";

    static function get($key){
        return Model_Hash::get_hash(self::CACHE_KEY,$key);
    }
    static function add($key,$value,$title){
        Model_Hash::add_hash(self::CACHE_KEY,$key,$value,$title);
    }
    static function update($key,$value,$title = ''){
        return Model_Hash::update_hash(self::CACHE_KEY,$key,$value,$title);
    }
    static function items(){
        $table = Model_Hash::TABLE;
        $items = self::_db()->select_rows("select * from $table where hash = ? order by `key` desc",self::CACHE_KEY);
        return $items;
    }
}