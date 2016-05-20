<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/1/19
 * Time: 下午2:53
 */
namespace Controller\Admin;

use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
use Model_Session;
use Model_Admin_Auth;
use Model_Admin_Permission;

class AbstractAdmin extends Model{
    function __construct()
    {
        Model_Session::session_start();
        if(empty($_SESSION['safe_logined'])) _throw("没有登陆",8001);
    }
    function check_permission($node){
        if(Model_Admin_Auth::get_user_id() > 0
            && !Model_Admin_Permission::check($node))
            _throw($node.": 未授权",800);
    }
}