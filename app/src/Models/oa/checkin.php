<?php
/**
 * 签到
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/2/1
 * Time: 下午5:26
 */
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
class Model_Oa_Checkin extends Model{
    const TABLE = "oa_checkin";
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
        //return;
       // self::_debug(__METHOD__);
        $table = self::TABLE;
        $rows = self::_db()->select_rows("select * from $table where is_sync = 0");
        foreach($rows as &$row){
            $daily_detail = json_decode($row['content'],1);
            $row['from_uid'] = $daily_detail['info']['uid'];
            $row['add_time'] = $daily_detail['info']['add_time'];
            $row['detail_name'] = $daily_detail['detail']['name'];
            $row['detail_hbr'] = $daily_detail['detail']['hbr'];
            $row['address'] = $daily_detail['detail']['address'];
            $row['latitude'] = $daily_detail['detail']['latitude'];
            $row['longitude'] = $daily_detail['detail']['longitude'];
            $row['latx'] = $daily_detail['detail']['latx'];
            $row['lngy'] = $daily_detail['detail']['lngy'];
            $row['proofs'] = implode("|",$daily_detail['detail']['proofs']);
            $copyer = "";
            foreach($daily_detail['copyer'] as $co){
                $copyer .= "|".$co['id']."|";
            }
            $row['copyer'] = empty($copyer)?"":$copyer;

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