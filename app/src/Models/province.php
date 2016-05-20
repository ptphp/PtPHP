<?php
/**
 * 省
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/2/1
 * Time: 下午5:26
 */
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
class Model_Province extends Model{
    const TABLE = "sys_dict_province";
    const KEY = "sys_p_";

    static function detail($id){
        if(!$id) return null;
        $name = self::_redis()->get(self::KEY.$id);
        if(!$name){
            $table = self::TABLE;
            $row = self::_db()->select_row("select name from $table where id = ?",$id);
            if($row){
                self::_redis()->set(self::KEY.$id,$row['name']);
                $name = $row['name'];
            }else{
                $name = "";
            }
        }
        return $name;
    }

}