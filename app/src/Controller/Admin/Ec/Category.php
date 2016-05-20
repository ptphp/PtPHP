<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/1/19
 * Time: 下午2:53
 */
namespace Controller\Admin\Ec;
use Controller\Admin\AbstractAdmin as AbstractAdmin;
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;

class Category extends AbstractAdmin{
    static function table(){
        return self::_table("ec_category");
    }
    static function pk(){
        return "cat_id";
    }
    /**
     * 删除
     * @param $id
     * @param $ids
     * @return array
     */
    function action_remove($id,$ids){
        $pk = self::pk();
        $table = self::table();
        if(1 || $id){
            $row = self::_db()->row("select * from $table where $pk = ?",$id);
            if(!$row) _throw("记录不存在");
            self::_db()->delete($table,array(
                "cat_id"=>$id
            ));
        }
        if($ids){
            $ids = handle_mysql_in_ids($ids);
            self::_db()->run_sql("delete from $table where $pk in ($ids)");
        }
        return array("msg"=>"删除成功");
    }

    function action_row($id){
        $pk = self::pk();
        $table = self::table();
        $row = self::_db()->row("select * from $table where $pk = ?",$id);
        return array(
            "row"=>$row
        );
    }

    function action_list(){
        return self::getRows();
    }

    static function getRows(){
        $pk = self::pk();
        $table = self::table();
        $sql = "select $pk as `key`,cat_name,cat_pid as pid,cat_name as label,concat('d_',$pk) as value from $table";

        $rows = self::_db()->select_rows($sql);
        if(empty($rows)){
            $row = array(
                "cat_name"=>"商品分类",
                "cat_pid"=>0
            );
            self::_db()->insert($table,$row);
            $rows = self::_db()->select_rows($sql);
        }

        $rows_key_name = array();
        $rows_key_pid = array();
        foreach($rows as &$row){
            $rows_key_name[$row['key']] = $row['cat_name'];
            $rows_key_pid[$row['key']]  = $row['pid'];
        }
        $res = array(
            "rows"=>Utils::list_to_tree($rows,"key","pid","children"),
            "rows_key_name"=>$rows_key_name,
            "rows_key_pid"=>$rows_key_pid,
        );
        return $res;
    }
    static function getSaveRow($row,$is_add = true){
        $row = json_decode($row,1);
        if(empty($row['cat_name'])) _throw("分类名不能为空");
        $_row = array(
            "cat_name"=>empty($row['cat_name']) ? null : $row['cat_name'],
            "cat_pid"=>empty($row['cat_pid']) ? 1 : $row['cat_pid']
        );

        return array(
            "row"=>$_row
        );
    }
    function action_update($id,$row){
        $pk = self::pk();
        $table = self::table();
        $res = self::getSaveRow($row,false);
        self::_db()->update($table,$res['row'],array(
            $pk=>$id
        ));
        return array(
            $pk=>$id,
        );
    }

    function action_add($row){
        $pk = self::pk();
        $table = self::table();
        $res = self::getSaveRow($row);
        $id = self::_db()->insert($table,$res['row']);
        return array(
            $pk=>$id,
        );
    }
}
