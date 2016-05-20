<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/1/19
 * Time: 下午2:53
 */
namespace Controller\Admin\Org;
use Controller\Admin\AbstractAdmin as AbstractAdmin;
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;

class Position extends AbstractAdmin{
    /**
     * 删除
     * @param $id
     * @param $ids
     * @return array
     */
    function action_remove($id,$ids){
        $table = self::table();
        if(1 || $id){
            $row = self::_db()->row("select * from $table where pot_id = ?",$id);
            if(!$row) _throw("记录不存在");
            self::_db()->delete($table,array(
                "pot_id"=>$id
            ));
        }
        if($ids){
            $ids = handle_mysql_in_ids($ids);
            self::_db()->run_sql("delete from $table where pot_id in ($ids)");
        }
        return array("msg"=>"删除成功");
    }

    function action_list(){
        $table = self::_table("org_department");

        $table_position = self::_table("org_position");
        $sql = "select dep_id as `key`,dep_name,dep_pid as pid,dep_name as label,concat('d_',dep_id) as value from $table";
        $rows = self::_db()->select_rows($sql);
        if(empty($rows)){
            $row = array(
                "dep_name"=>"部门",
                "dep_pid"=>0
            );
            self::_db()->insert($table,$row);
            $rows = self::_db()->select_rows($sql);
        }

        $departments_rows = array();
        $departments_pid_rows = array();
        $positions = self::_db()->rows("select p.* from $table_position as p left join $table as d on d.dep_id = p.dep_id");
        $_positions = array();
        foreach($positions as $position){
            $_positions[$position['dep_id']][] = $position;
        }
        foreach($rows as &$row){
            $departments_rows[$row['key']] = $row['dep_name'];
            $departments_pid_rows[$row['key']] = $row['pid'];
            if(!empty($_positions[$row['key']])){
                $row['positions'] =  $_positions[$row['key']];
            }
        }
        $res = array(
            "rows"=>Utils::list_to_tree($rows,"key","pid","children"),
            "departments_rows"=>$departments_rows,
            "departments_pid_rows"=>$departments_pid_rows,
        );
        return $res;
    }
    static function getSaveRow($row,$is_add = true){
        $row = json_decode($row,1);
        if(empty($row['pot_name'])) _throw("职位名不能为空");
        $_row = array(
            "pot_name"=>$row['pot_name'],
            "dep_id"=>empty($row['dep_id']) ? 1 : $row['dep_id']
        );

        return array(
            "row"=>$_row
        );
    }
    static function table(){
        return self::_table("org_position");
    }
    function action_update($id,$row){
        $table = self::table();
        $res = self::getSaveRow($row,false);
        self::_db()->update($table,$res['row'],array(
            "pot_id"=>$id
        ));
        return array(
            "pot_id"=>$id,
        );
    }

    function action_add($row){
        $table = self::table();
        $res = self::getSaveRow($row);
        $id = self::_db()->insert($table,$res['row']);
        return array(
            "pot_id"=>$id,
        );
    }
}
