<?php
/**
 * 日报
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/2/1
 * Time: 下午5:26
 */
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
class Model_Oa_Daily extends Model{
    const TABLE = "oa_daily";
    static function add($row){
        $_row = array(
            "add_time"     => Utils::date_time_now(),
        );
        return self::_db()->insert(self::TABLE,$_row);
    }
    static function update($id,$row){
        $_row = array(

        );
        self::_db()->update(self::TABLE,$_row,array(
            "id"=>$id
        ));
        return $id;
    }
    static function remove($id){

    }
    static function list_rows($condition = array(),$pager = array() ,$order = array()){

    }
    static function sync(){
        $table = self::TABLE;
        $rows = self::_db()->select_rows("select * from $table where is_sync = 0");
        foreach($rows as &$row){
            $daily_detail = json_decode($row['content'],1);
            $row['from_uid'] = $daily_detail['info']['uid'];
            $row['type'] = $daily_detail['info']['type'];
            $row['add_time'] = $daily_detail['info']['add_time'];
            $row['report_date'] = $daily_detail['info']['report_date'];
            $row['done'] = $daily_detail['info']['content'];
            $row['todo'] = empty($daily_detail['info']['tcontent'])?"":$daily_detail['info']['tcontent'];
            $copyer = "";
            foreach($daily_detail['copyer'] as $co){
                $copyer .= "|".$co['id']."|";
            }
            $row['copyer'] = empty($copyer)?"":$copyer;
            $row['comments'] = empty($daily_detail['comments'])?json_encode(array()):json_encode($daily_detail['comments']);

            $_row = $row;
            unset($_row['key']);
            unset($_row['id']);
            $_row['is_sync'] = 1;
            self::_db()->update(self::TABLE,$_row,array(
                "id"=>$row['id']
            ));

        }
    }
}