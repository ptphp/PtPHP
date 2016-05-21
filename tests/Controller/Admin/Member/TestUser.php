<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/1/19
 * Time: 下午2:53
 */
namespace Controller\Admin\Member;
use PtPHP\UnitTest as UnitTest;
use Exception;
class TestUser extends UnitTest{
    var $obj = null;
    function __construct()
    {
        \Model_Admin_Auth::set_login_session(-1);
        $this->obj = new User();
    }
    function test_action_row(){
        $row = $this->obj->action_row(4);
        var_export($row);
    }
    function test_action_add(){
        $mobile = "18601628937";
        $row = json_encode(array(
            "mobile"=>$mobile,
            "password"=>"111111"
        ));
        $res = $this->obj->action_add($row);
        var_export($res);
    }

    function test_action_update(){

        $row = json_encode(array(
            "mobile"=>"",
            "password"=>""
        ));
        $res = $this->obj->action_update(5,$row);
        var_export($res);
    }

    function test_action_list(){
        $res = $this->obj->action_list(null,null,null,null,null);
        var_export($res);
    }

    function test_action_remove(){
        $res = $this->obj->action_remove(1,"1");
        var_export($res);
    }
}
