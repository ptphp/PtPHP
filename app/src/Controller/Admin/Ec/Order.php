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
use Symfony\Component\Config\Definition\Exception\Exception;

class Order extends AbstractAdmin{
    static function table(){
        return self::_table("ec_order");
    }
    static function pk(){
        return "ord_id";
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
        if($id){
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
        $left_join = self::get_join_sql();
        $row = self::_db()->row("select o.* from $table as o $left_join where o.{$pk} = ?",$id);
        return array(
            "row"=>$row
        );
    }

    static function get_join_sql(){
        $table_pay = self::_table("ec_order_pay");
        $table_ship = self::_table("ec_order_ship");
        $left_join  = "left join $table_pay as p on p.ord_id = o.ord_id ";
        $left_join  .= "left join $table_ship as s on s.ord_id = o.ord_id ";
        return $left_join;
    }

    function action_list($limit,$page,$sorter,$search,$filters){
        $pk = self::pk();
        $table_alias = "o";
        $table = self::table();
        $select_field = "{$table_alias}.*,{$table_alias}.{$pk} as `key`,p.*,s.*";

        $limit = empty($limit) ? 10 : intval($limit);
        $page  = empty($page)  ? 1  : intval($page);
        $condition = array();
        if(!empty($search)){
            self::_debug($search);
            $condition = json_decode($search,1);
        }
        self::_debug($condition);
        if(!empty($filters)){
            $filters = json_decode($filters,1);
            self::_debug($filters);
            $condition = array_merge($condition,$filters);
        }
        self::_debug($condition);

        $where = 'where 1 = 1';
        $args = array();
        if(!empty($condition['subject'])){
            $where .= " and {$table_alias}.subject like ?";
            $args[] = "%".$condition['subject']."%";
        }

        $count_res = self::_db()->select_row("SELECT COUNT({$table_alias}.{$pk}) AS total FROM $table as {$table_alias} $where",$args);
        $records = $count_res['total'];
        $total_pages = $records > 0  ? ceil($records/$limit) : 1;
        $skip = ($page - 1) * $limit;

        $sorter_order_tpl = array("ascend"=>"asc","descend"=>"desc");
        if(!empty($sorter)) $sorter = json_decode($sorter,1);
        $sorter_field_tpl = array("ord");
        $sort_field = !empty($sorter['field']) && in_array($sorter['field'],$sorter_field_tpl) ? $sorter['field']  : $table_alias.".".$pk;
        $sort_order = !empty($sorter['order']) && !empty($sorter_order_tpl[$sorter['order']]) ? $sorter_order_tpl[$sorter['order']]:"desc";
        $order  = "ORDER BY $sort_field $sort_order";
        $left_join = self::get_join_sql();
        $sql = "SELECT $select_field FROM $table as {$table_alias} $left_join $where $order LIMIT $skip,$limit ";
        //self::_debug($sql);
        $rows = self::_db()->rows($sql,$args);

        $cats = \Controller\Admin\Ec\Category::getRows();

        $res = array(
            "total"=>$records,
            "page"=>$page,
            "total_pages"=>$total_pages,
            "limit"=>$limit,
            "skip"=>$skip,
            "rows"=>$rows,
            "cats"=>$cats,
        );
        if(!self::is_production())
            $res['debug'] = array(
                "sql"=>$sql,
                "args"=>$args,
                "params"=>array($limit,$page,$sorter,$search,$filters,$condition),
            );
        return $res;
    }
    static function getSaveRowValue($row,$key,$default=null){
        return empty($row[$key]) ? $default : $row[$key];
    }
    static function getSaveRow($row,$is_add = true){
        $row = json_decode($row,1);
        if(empty($row['god_name'])) _throw("商品名不能为空");

        $_row = array(
            "god_name"=>self::getSaveRowValue($row,"god_name",""),
        );

        return array(
            "row"=>$_row
        );
    }
    function action_update($id,$row){
        $res = self::getSaveRow($row,false);
        $table = self::table();
        $pk = self::pk();
        self::_db()->update($table,$res['row'],array(
            $pk=>$id
        ));
        return array($pk=>$id);
    }

    function action_add($row){
        $res = self::getSaveRow($row);
        $table = self::table();
        $pk = self::pk();
        $res['row']['add_time'] = Utils::date_time_now();
        $id = self::_db()->insert($table,$res['row']);
        return array($pk=>$id);
    }
}
