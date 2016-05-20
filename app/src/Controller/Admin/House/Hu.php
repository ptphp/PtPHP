<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/1/19
 * Time: 下午2:53
 */
namespace Controller\Admin\House;
use Controller\Admin\AbstractAdmin as AbstractAdmin;

use PtPHP\Model as Model;
use PtPHP\Utils as Utils;

class Hu extends AbstractAdmin{
    function action_remove(){
        $id = Utils::I("id");
        self::_db()->delete("small",array(
            "id"=>$id
        ));
        return array("msg"=>"删除成功");
    }

    function action_row(){
        $id = Utils::I("id");
        $row = self::_db()->select_row("select * from small where id = ?",$id);
        return array("row"=>$row);
    }
    function action_list($id){
        $rows = self::_db()->select_rows("select *,id as `key` from small where pid = ? order by id desc ",$id);
        return $rows;
    }
    function action_detail(){
        $id = Utils::I("id");
        $detail = self::_db()->select_row("select * from small where id = ?",$id);
        return array(
            "detail"=>$detail,
        );
    }
    static function getSaveRow(){
        $row = Utils::I("row");
        $row = json_decode($row,1);
        $_row = array(
            "pid"=>!empty($row['pid']) ? $row['pid']:"",
            "type"=>!empty($row['type']) ? $row['type']:"",
            "pics"=>!empty($row['pics']) ? $row['pics']:"",
        );
        return $_row;
    }
    function action_update(){
        $id = Utils::I("id");
        $row = self::getSaveRow();
        self::_db()->update("small",$row,array(
            "id"=>$id
        ));
        return array("id"=>$id);
    }
    function action_add(){
        $row = self::getSaveRow();
        $id = self::_db()->insert("small",$row);
        return array("id"=>$id);
    }
}