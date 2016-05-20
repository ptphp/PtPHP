<?php
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

//include PATH_LIBS."/qiniu/php-sdk/autoload.php";

/**
 * 七牛上传
 */
class Model_Tools_Qiniu{
    static function get_res_url($res){
        return "http://".PtConfig::$qiniu['domain']."/".$res['key'];
    }
    static function get_auth(){
        $accessKey = PtConfig::$qiniu['access_key'];
        $secretKey = PtConfig::$qiniu['secret_key'];
        $auth = new Auth($accessKey, $secretKey);
        return $auth;
    }
    static function get_uptoken(){
        $bucket = PtConfig::$qiniu['bucket'];
        $auth = self::get_auth();
        $upToken = $auth->uploadToken($bucket);
        return $upToken;
    }
    function action_token(){
        $upToken = self::get_uptoken();
        echo json_encode(array("uptoken"=>$upToken));exit;
    }

    /**
     * @param $local_path  要上传文件的本地路径
     * @param $remote_path 上传到七牛后保存的文件名
     * @throws Exception
     */
    static function upload_file($local_path,$remote_path){
        if(substr($remote_path,0,1) == "/") $remote_path = substr($remote_path,1);
        $upToken = self::get_uptoken();
        //echo $upToken;exit;
        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->putFile($upToken, $remote_path, $local_path);
        if ($err !== null) {
            throw new Exception($err->getResponse()->error,$err->getResponse()->statusCode);
        }
        return $ret;
    }

    static function upload_content($content,$remote_path){
        if(substr($remote_path,0,1) == "/") $remote_path = substr($remote_path,1);
        $upToken = self::get_uptoken();
        //echo $upToken;exit;
        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->put($upToken, $remote_path, $content);
        if ($err !== null) {
            throw new Exception($err->getResponse()->error,$err->getResponse()->statusCode);
        }
        return $ret;
    }

    static function list_file($bucketName,$path = null){
        if($path && substr($path ,0,1) == "/") $path = substr($path,1);
        //echo $path;exit;
        //$path = null;
        $auth = self::get_auth();
        $bucketManager = new BucketManager($auth);
        list($items, $marker, $error) = $bucketManager->listFiles($bucketName, $path, null, 2);
        return $items;
    }


}