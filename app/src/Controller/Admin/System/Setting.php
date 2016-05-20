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

}