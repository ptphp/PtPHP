<?php
/**
 * 日志
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/2/1
 * Time: 下午5:26
 */
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
class Model_Log extends Model{
    const TABLE = "sys_action_log";
    static function add($msg,$method){
        $ip = Utils::ip();
        $date = Utils::date_time_now();
//        $row = array(
//            "ip"       => $ip,
//            "add_time" => $date,
//            "msg"      => $msg,
//            "method"   => $method,
//            "user_id"  => Model_Admin_Auth::get_user_id(),
//        );
        $row = array(
            "action_ip"       => Utils::ip(true),
            "create_time"     => time(),
            "remark"          => $msg,
            "model"           => $method,
            "user_id"  => Model_Admin_Auth::get_user_id(),
        );
        self::_db()->insert(self::TABLE,$row);
    }

    static function list($condition = array(),$pager = array() ,$order = array()){
        $sort_field = !in_array($order['sort_field'],
            array(
                "id",
                "model",
                "create_time",
            )
        ) ? "id":$order['sort_field'];

        $table = self::TABLE;
        $where = 'where 1= 1';
        $args = array();

        if(!empty($condition['user_id'])){
            $where .= " and user_id = ?";
            $args[] = $condition['user_id'];
        }
        if(!empty($condition['ip'])){
            $where .= " and action_ip = ?";
            $args[] = ip2long($condition['ip']);
        }
        if(!empty($condition['method'])){
            $where .= " and model = ?";
            $args[] = $condition['method'];
        }
        if(!empty($condition['start_time'])){
            $where .= " and create_time >= ?";
            $args[] = $condition['start_time'];
        }
        if(!empty($condition['end_time'])){
            $where .= " and create_time <= ?";
            $args[] = $condition['end_time'];
        }
        if(!empty($condition['remark'])){
            $where .= " and remark like ?";
            $args[] = "%".$condition['remark']."%";
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
        $sql = "SELECT *  FROM $table $where ORDER BY $sort_field $sort_type LIMIT $skip,$limit ";
        //_throw($sql);
        self::_debug(array($sql,$args,$condition,$pager,$order,$sort_field,$sort_type));
        $rows = self::_db()->select_rows($sql,$args);
        foreach($rows as &$row){
            $row['key'] = $row['id'];
            $row['create_time'] = date("Y-m-d H:i:s",$row['create_time']);
            $row['action_ip'] = long2ip($row['action_ip']);
        }
        return array(
            "total_pages" => $total_pages,
            "total"       => $records,
            "rows"        => $rows,
        );
    }
}