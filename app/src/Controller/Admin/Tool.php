<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/1/19
 * Time: 下午2:53
 */
namespace Controller\Admin;
use Model_Tools_Qiniu;
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
use Model_Session;
use Model_Auth;
use Model_Admin_Auth;
use PtConfig;
class Tool extends Model{
    function action_upload(){
        if(empty($_FILES)) _throw("请选择上传文件");
        $file_path = $_FILES['file']['tmp_name'];
        $file_name = "upload/img/".date("YmdHis")."/".rand(10000,99999)."/".$_FILES['file']['name'];

        $res = Model_Tools_Qiniu::upload_file($file_path,$file_name);
        $url = Model_Tools_Qiniu::get_res_url($res);
        self::_debug($url);

        if(Utils::I("simditor")){
            echo json_encode(array(
                "success"=>true,
                "msg"=>"ok",
                "file_path"=>$url
            ));exit;
        }else{
            return array(
                "url"=>$url
            );
        }
        //self::_debug($_FILES);
        //self::_debug($_REQUEST);
    }
}