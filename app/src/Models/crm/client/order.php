<?php
/**
 * 客户订单
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/2/1
 * Time: 下午5:26
 */
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
class Model_Crm_Client_Order extends Model{
    const TABLE = "crm_client";
    const TABLE_ORDER = 'crm_client_order';
    const TABLE_PORDER = 'crm_client_porder';
    const TABLE_TRACE = 'crm_client_trace';
    const TABLE_ADVANCE = 'crm_client_advance';
    const TABLE_REVISIT = "crm_client_revisit";
    static function row($id){
        $table = self::TABLE_ORDER;
        $row = self::_db()->select_row("select * from $table where id = ?",$id);
        if(empty($row)) _throw("没有找到订单");
        $row['items'] = self::_db()->select_rows("select *,id as `key` from crm_client_order_item where order_id = ?",$id);
        return $row;
    }
    static function getOrderSaveRow($_row){
        if(empty($_row['client_id'])) _throw("没有选择客户!");
        if(empty($_row['orderno'])) _throw("订单编号不能为空!");
        return array(
            "orderno"=>$_row['orderno'],
            "client_id"=>$_row['client_id'],
            "total"=>$_row['total'],
            "total_pos"=>$_row['total_pos'],
            "total_cash"=>$_row['total_cash'],
            "give_amount"=>$_row['give_amount'],
            "yl_uid"=>$_row['yl_uid'],
            "yl_name"=>$_row['yl_name'],
            "yl_note"=>$_row['yl_note'],
            "yl_time"=>empty($_row['yl_time'])?null:$_row['yl_time'],
            "cw_note"=>$_row['cw_note'],
            "cw_uid"=>$_row['cw_uid'],
            "cw_name"=>$_row['cw_name'],
            "cw_time"=>empty($_row['cw_time'])?null:$_row['cw_time'],
        );
    }
    static function getOrderItemsSaveRows($_row){
        //_throw(json_encode(empty($_row['items'])));
        if(empty($_row['items'])) return array();
        $items =  $_row['items'];
        $_items = array();
        if($items){
            if(!is_array($items)) $items = json_decode($items,1);
            if($items){
                foreach($items as $item){
                    $row = array(
                        "item_id"=>$item['item_id'],
                        "item_amount"=>$item['item_amount'],
                        "item_name"=>$item['item_name'],
                        "item_unit"=>$item['item_unit'],
                        "item_type"=>$item['item_type'],
                        "item_num"=>empty($item['item_num']) ? 0 : $item['item_num'],
                        "is_give"=>$item['is_give'],
                        "doct_name"=>$item['doct_name'],
                        "doct_uid" =>$item['doct_uid'],
                        "real_ys_uid" =>$item['real_ys_uid'],
                        "real_ys_name" =>$item['real_ys_name'],
                        "yl_note" =>$item['yl_note'],
                        "real_time"=>empty($item['real_time'])?null:$item['real_time'],
                        "appoint_time"=>empty($item['appoint_time'])?null:$item['appoint_time'],
                    );
                    $_items[] = $row;
                }
            }
        }
        return $_items;
    }
    static function add($_row){
        $row = self::getOrderSaveRow($_row);
        if($row['orderno']){
            $table = self::TABLE_ORDER;
            $order = self::_db()->select_row("select * from $table where orderno = ?",$row['orderno']);
            if($order)
                _throw("订单号:".$row['orderno']." 已存在");
        }

        $row['add_time'] = Utils::date_time_now();
        $row['op_uid'] = Model_Admin_Auth::get_user_id();
        $staff_info = Model_Admin_Staff::detail_by_uid($row['op_uid']);
        $row['op_name'] = $staff_info['name'];
        $items = self::getOrderItemsSaveRows($_row);

        $id =  self::_db()->insert(self::TABLE_ORDER,$row);
        foreach($items as &$item){
            $item['order_id'] = $id;
        }
        if($items)
            self::_db()->insert("crm_client_order_item",$items);
        return $id;
    }

    static function update($id,$_row){
        $row = self::getOrderSaveRow($_row);
        self::_db()->delete("crm_client_order_item",array(
            "order_id"=>$id
        ));
        $items = self::getOrderItemsSaveRows($_row);
        foreach($items as &$item){
            $item['order_id'] = $id;
        }
        if($items){
            self::_db()->insert("crm_client_order_item",$items);
        }
        self::_debug($row);
        if(!empty($row['orderno'])) unset($row['orderno']);
        self::_db()->update(self::TABLE_ORDER,$row,array(
            "id"=>$id
        ));
        return $id;
    }

    static function remove($id){
        self::_debug(array($id,self::TABLE_PORDER));
        self::_db()->delete(self::TABLE_ORDER,array(
            "id"=>$id
        ));
        self::_db()->delete("crm_client_order_item",array(
            "order_id"=>$id
        ));
    }

    static function list_rows($condition = array(),$pager = array() ,$order = array()){
        $sort_field = !in_array($order['sort_field'],
            array(
                "id",
            )
        ) ? "id":$order['sort_field'];

        $table = self::TABLE_ORDER;
        $where = 'where 1= 1';
        $args = array();
        if(!empty($condition['client_id'])){
            $where .= " and client_id = ?";
            $args[] = $condition['client_id'];
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
        $sql = "SELECT *,id as `key`  FROM $table $where ORDER BY $sort_field $sort_type LIMIT $skip,$limit ";
        //_throw($sql);
        //self::_debug(array($sql,$args,$condition,$pager,$order,$sort_field,$sort_type));
        $rows = self::_db()->select_rows($sql,$args);
        foreach($rows as &$row){
            $op_uid = $row['op_uid'];
            $staff_info = Model_Admin_Staff::get_staff_info_by_uid_from_cache($op_uid);
            if($staff_info){
                $row['op_name'] = $staff_info['name'];
                $row['op_avatar'] = $staff_info['avatar'];
            }else{
                $row['op_name'] = "";
                $row['op_avatar'] = "";
            }
            $row['items'] = self::_db()->select_rows("select i.id as `key`,p.*,i.item_num as num,i.doct_uid,i.doct_name,i.appoint_time
                  from crm_client_order_item as i
                  left join crm_product as p on p.id = i.item_id
                  where i.order_id = ?",$row['id']);
        }
        return array(
            "total_pages" => $total_pages,
            "total"       => $records,
            "rows"        => $rows,
        );
    }

}