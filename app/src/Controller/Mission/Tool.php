<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/1/19
 * Time: 下午2:53
 */
namespace Controller\Mission;
use Model_Tools_Qiniu;
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
use Model_Session;
use Model_Auth;
use Model_Admin_Auth;
use PtConfig;
class Tool extends Model{
    function action_upload_content_base64($content){
        $url = self::upload_content($content);
        self::_debug($url);
        return array(
            "url"=>$url
        );
    }
    static function upload_content($content){
        $file_name = "upload/img/mission/".date("YmdHis")."/".md5($content);
        list($t,$content) = explode(";base64,",$content);
        $content = base64_decode($content);
        $res = Model_Tools_Qiniu::upload_content($content,$file_name);
        $url = Model_Tools_Qiniu::get_res_url($res);
        return $url;
    }
}