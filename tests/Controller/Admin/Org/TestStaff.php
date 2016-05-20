<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/1/19
 * Time: 下午2:53
 */
namespace Controller\Admin\Org;
use PtPHP\UnitTest as UnitTest;
use Symfony\Component\Config\Definition\Exception\Exception;

class TestStaff extends UnitTest{
    var $obj = null;
    function __construct()
    {

        \Model_Admin_Auth::set_login_session(-1);
        $this->obj = new Staff();
    }

    function setUp(){

    }

    function test_action_row(){
        $mobile = "18601628932";
        $stf_id = \Model_Admin_Staff::get_staff_id_by_mobile($mobile);
        $row = $this->obj->action_row($stf_id);
        var_export($row);
    }
    function test_get_stf_id_by_mobile(){
        $mobile = "13555555553";
        $stf_id = \Model_Admin_Staff::get_staff_id_by_mobile($mobile);
        $res = \Model_Admin_Staff::get_auth_user_by_stf_id($stf_id);
        $password = $res['password'];
        $salt = $res['salt'];
        $_password = \Model_Admin_Auth::gen_password("111111",$salt);
        var_export($_password);
    }
    function test_action_add(){
        $mobile = "18601628937";
        $row = json_encode(array(
            "stf_name"=>"李六",
            "mobile"=>$mobile,
            "password"=>"111111"
        ));
        $res = $this->obj->action_add($row);
        var_export($res);
    }

    function test_action_update(){
        $mobile = "18601628937";
        $stf_id = \Model_Admin_Staff::get_staff_id_by_mobile($mobile);
        $auth_user_info = \Model_Admin_Staff::get_auth_user_by_stf_id($stf_id);
        var_export($auth_user_info);
        $staff_info = $this->obj->get_detail($stf_id);
        var_export($staff_info);
        $row = json_encode(array(
            "stf_name"=>"李六",
            "mobile"=>"18601628932",
            "password"=>""
        ));
        $res = $this->obj->action_update($stf_id,$row);
        var_export($res);
    }

    function test_action_list(){

    }

    function test_action_remove(){
        $mobile = "18601628932";
        $stf_id = \Model_Admin_Staff::get_staff_id_by_mobile($mobile);
        if($stf_id){
            $res = $this->obj->action_remove($stf_id,"1");
            var_export($res);
        }else{
            throw new Exception("stf_id is null");
        }
    }
}
