<?php
/**
 * 产品
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/2/1
 * Time: 下午5:26
 */
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
class Model_Crm_Product extends Model{
    const TABLE = "crm_product";
    static function add($name,$type,$amount,$sno,$unit){
        $row = array(
            "name"          => $name,
            "type"          => $type,
            "amount"       => $amount,
            "sno"          => $sno,
            "unit"          => $unit,
            "add_time"     => Utils::date_time_now(),
        );
        return self::_db()->insert(self::TABLE,$row);
    }
    static function update($id,$name,$type,$amount,$sno,$unit){
        $row = array(
            "name"          => $name,
            "type"          => $type,
            "amount"       => $amount,
            "unit"          => $unit,
            "sno"          => $sno,
        );
        self::_db()->update(self::TABLE,$row,array(
            "id"=>$id
        ));
        return $id;
    }
    static function remove($id){
        $row['del_time'] = Utils::date_time_now();
        $row['is_del'] = 1;
        self::_db()->update(self::TABLE,$row,array(
            "id"=>$id
        ));
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
        $sql = "SELECT unit,name,add_time,id as `key`,id,sno,type,amount  FROM $table $where ORDER BY $sort_field $sort_type LIMIT $skip,$limit ";
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