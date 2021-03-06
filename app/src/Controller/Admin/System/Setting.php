<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/3/23
 * Time: 下午5:00
 */
namespace Controller\Admin\System;
use Controller\Admin\AbstractAdmin as AbstractAdmin;
use PtPHP\Model as Model;
use Model_Admin_Auth;
use PtConfig;
use Model_Admin_Permission;
class Setting extends AbstractAdmin{
    function action_menu(){
        return $this->get_menu();
    }
    function get_menu(){
        $menu_path = PATH_APP."/config/json/menus.json";
        if(self::is_development()){
            $menu_path = PATH_APP."/config/json/menus_dev.json";
        }
        if(!is_file($menu_path)) _throw("not found menus.json");
        $menus = json_decode(file_get_contents($menu_path),1);
        if(Model_Admin_Auth::get_user_id() == -1) return $menus;
        $_menus = array();
        foreach($menus as &$menu){
            if(Model_Admin_Permission::check($menu['title'])){
                if(!empty($menu['sub'])){
                    $sub = array();
                    foreach($menu['sub'] as &$sub_menu){
                        if(Model_Admin_Permission::check($menu['title']."/".$sub_menu['title'])){
                            $sub[] = $sub_menu;
                        }
                    }
                    if(!empty($sub)) $menu['sub'] = $sub;
                    if(!empty($sub)) $_menus[] = $menu;
                }else{
                    $_menus[] = $menu;
                }
            }
        }
        return $_menus;
    }
    /**
     * 获取所有项
     * @api_url?controller=admin/system/setting&action=info
     * @return array
     */
    function action_info(){
        $user_id = Model_Admin_Auth::get_user_id();
        $is_super_admin = $user_id == -1;

        $info = array(
            "site_title"=>PtConfig::$siteAdminTitle,
            "menus"=>$this->get_menu(),
            "interval"=>intval(22000)
        );
        $res = array(
            "setting"=>$info,
            "permissions"=>array(),
            "is_super_admin"=> $is_super_admin,
        );
        //self::_debug($res);
        return $res;
    }

    static function table(){
        return self::_table("sys_setting");
    }
    static function table_alias(){
        return "s";
    }

    static function pk(){
        return "set_id";
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
                $pk=>$id
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
        $table_alias = self::table_alias();
        $left_join = self::get_join_sql();
        $row = self::_db()->row("select {$table_alias}.*,{$table_alias}.{$pk} as `key` from $table as s $left_join where {$table_alias}.{$pk} = ?",$id);
        return array(
            "row"=>$row
        );
    }

    static function get_join_sql(){
        $left_join  = "";
        return $left_join;
    }
    function action_list($limit,$page,$sorter,$search,$filters){
        $pk = self::pk();
        $table_alias = self::table_alias();
        $table = self::table();
        $select_field = "{$table_alias}.*,{$table_alias}.{$pk} as `key`";

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
//        if(!empty($condition['mobile'])){
//            $where .= " and {$table_alias}.mobile like ?";
//            $args[] = "%".$condition['mobile'];
//        }
        if(!empty($condition['set_key'])){
            $where .= " and {$table_alias}.set_key like ?";
            $args[] = "%".$condition['set_key']."%";
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
        self::_debug(array($sql,$args));
        $rows = self::_db()->rows($sql,$args);

        $res = array(
            "total"=>$records,
            "page"=>$page,
            "total_pages"=>$total_pages,
            "limit"=>$limit,
            "skip"=>$skip,
            "rows"=>$rows,
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
        if(empty($row['set_key'])) _throw("key不能为空");

        $_row = array(
            "set_key"=>self::getSaveRowValue($row,"set_key",""),
            "set_title"=>self::getSaveRowValue($row,"set_title",""),
            "set_value"=>self::getSaveRowValue($row,"set_value",""),
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
        $res = $this->action_row($id);
        $res[$pk] = $id;
        return $res;
    }

    function action_add($row){
        $res = self::getSaveRow($row);
        $table = self::table();
        $pk = self::pk();
        $id = self::_db()->insert($table,$res['row']);
        $res = $this->action_row($id);
        $res[$pk] = $id;
        return $res;
    }
}