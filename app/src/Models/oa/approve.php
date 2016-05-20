<?php
/**
 * 审批批令
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/2/1
 * Time: 下午5:26
 */
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
class Model_Oa_Approve extends Model{
    const TABLE = "oa_approve";
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
    static function getStatusTip($status,$type){
        $tip = "";
        if($type == "指令"){
            if($status == 0){
                $tip = "执行中";
            }
            if($status == 1){
                $tip = "已完成";
            }
            if($status == 2){
                $tip = "已完成";
            }
            if($status == -1){
                $tip = "未完成";
            }
        }else{
            if($status == 0){
                $tip = "申请中";
            }
            if($status == 1){
                $tip = "已批准";
            }
            if($status == 2){
                $tip = "已转交";
            }
            if($status == -1){
                $tip = "驳回";
            }
        }

        return $tip;
    }
    static function list_rows($condition = array(),$pager = array() ,$order = array()){

    }

    static function sync(){
        $table = self::TABLE;
        $rows = self::_db()->select_rows("select * from $table where is_sync = 0");
        foreach($rows as &$row){
            $daily_detail = json_decode($row['content'],1);
            $row['from_uid'] = $daily_detail['info']['uid'];
            $row['sno'] = $daily_detail['info']['sno'];
            $row['type'] = $daily_detail['info']['type'];
            $row['add_time'] = $daily_detail['info']['add_time'];
            $row['up_time'] = $daily_detail['info']['up_time'];
            $row['status'] = $daily_detail['info']['status'];
            $row['priority'] = $daily_detail['info']['priority'];

            $row['apply_info'] = json_encode($daily_detail['apply']);
            $row['comments'] = json_encode($daily_detail['comments']);
            $row['approver_info'] = json_encode($daily_detail['approver']);

            $copyer = "";
            foreach($daily_detail['copyer'] as $co){
                $copyer .= "|".$co['id']."|";
            }
            $row['copyer'] = empty($copyer)? "" :$copyer;

            $approver = "";
            foreach($daily_detail['approver'] as $co){
                $approver .= "|".$co['id']."|";
            }
            $row['approver'] = empty($approver)? "" :$approver;

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