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

class House extends AbstractAdmin{
    function action_remove(){
        $id = Utils::I("id");
        self::_db()->delete("property_release",array(
            "id"=>$id
        ));
        return array("msg"=>"删除成功");
    }

    function action_row(){
        $id = Utils::I("id");
        $row = self::_db()->select_row("select * from property_release where id = ?",$id);
        $hu_obj = new Hu();
        return array("row"=>$row,"hus"=>$hu_obj->action_list($id));
    }
    function action_list(){
        $rows = self::_db()->select_rows("select *,id as `key` from property_release order by id desc");
        return array("rows"=>$rows);
    }
    function action_detail(){
        $id = Utils::I("id");
        $detail = self::_db()->select_row("select * from property_release where id = ?",$id);
        return array(
            "detail"=>$detail,
        );
    }
    static function getSaveRow(){
        $row = Utils::I("row");
        $row = json_decode($row,1);
        if(empty($row['title'])) _throw("title不能为空");
        $_row = array(
            "title"=>empty($row['title']) ? "" : $row['title'],
            "pics"=>empty($row['pics']) ? "" : $row['pics'],
            "name"=>empty($row['name']) ? "" : $row['name']
        );

        return $_row;
    }
    function action_update(){
        $id = Utils::I("id");
        $row = self::getSaveRow();
        self::_db()->update("property_release",$row,array(
            "id"=>$id
        ));
        return array("id"=>$id);
    }
    function action_add(){
        $row = self::getSaveRow();
        $id = self::_db()->insert("property_release",$row);
        return array("id"=>$id);
    }
}