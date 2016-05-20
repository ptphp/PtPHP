<?php
/**
 * 地区
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/2/1
 * Time: 下午5:26
 */
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
class Model_Crm_Area extends Model{
    const TABLE = "sys_dict_area";
    const KEY = "sys_a_";
    static function cache_row($id,$name){
        self::_redis()->set(self::KEY.$id,$name);
    }
    static function add($name){
        $row = array(
            "name"          => $name,
        );
        $id = self::_db()->insert(self::TABLE,$row);
        self::cache_row($id,$name);
        return $id;
    }
    static function detail($id){
        if(!$id) return null;
        $name = self::_redis()->get(self::KEY.$id);
        if(!$name){
            $table = self::TABLE;
            $row = self::_db()->select_row("select name from $table where id = ? and is_del = 0",$id);
            if($row){
                self::cache_row($id,$row['name']);
                $name = $row['name'];
            }else{
                $name = "";
            }
        }
        return $name;
    }
    static function update($id,$name){
        $row = array(
            "name"          => $name,
        );
        self::_db()->update(self::TABLE,$row,array(
            "id"=>$id
        ));
        self::cache_row($id,$name);
    }
    static function remove($id){
        $row['del_time'] = Utils::date_time_now();
        $row['is_del'] = 1;
        self::_db()->update(self::TABLE,$row,array(
            "id"=>$id
        ));
        self::_redis()->del(self::KEY.$id);
    }
    static function list_rows($condition = array(),$pager = array() ,$order = array()){
        $sort_field = !in_array($order['sort_field'],
            array(
                "id",
                "name",
            )
        ) ? "id":$order['sort_field'];

        $table = self::TABLE;
        $where = 'where is_del = 0';
        $args = array();

        if(!empty($condition['name'])){
            $where .= " and name like ?";
            $args[] = "%".$condition['name']."%";
        }

        $sort_type_tpl = array(
            "ascend"=>"asc",
            "descend"=>"desc"
        );
        $sort_type = !in_array($order['sort_type'],array_keys($sort_type_tpl)) ? "desc":$sort_type_tpl[$order['sort_type']];
        //_throw($sort_type);
        $count_res = self::_db()->select_row("SELECT COUNT(id) AS total FROM $table $where",$args);
        $records = $count_res['total'];
        $page = $pager['page'];
        $limit = $pager['limit'];
        if( $records > 0 ) {
            $total_pages = ceil($records/$limit);
        }
        else {
            $total_pages = 1;
        }
        $skip = ($page - 1) * $limit;
        $sql = "SELECT name,id as `key`,id FROM $table $where ORDER BY $sort_field $sort_type LIMIT $skip,$limit ";
        //_throw($sql);
        //self::_debug(array($sql,$args,$condition,$pager,$order,$sort_field,$sort_type));
        $rows = self::_db()->select_rows($sql,$args);
//        foreach($rows as &$row){
//            $row['key'] = $row['id'];
//        }
        return array(
            "total_pages" => $total_pages,
            "total"       => $records,
            "rows"        => $rows,
        );
    }
}