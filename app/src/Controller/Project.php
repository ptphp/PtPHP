<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/1/19
 * Time: 下午2:53
 */
namespace Controller;
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
use Controller\Mission\Auth;
use Model_Session;
use Model_Auth;
use Model_Admin_Auth;
use PtConfig;
use Model_Wechat_Api;
use Model_Wechat_User;
class Project extends Model{
    function __construct(){

    }

    static function get_project_apps(){
        $path = PATH_PRO."/app/config/app.json";
        if(!is_file($path)) die("not found app.json");
        return json_decode(file_get_contents($path),1);
    }
    static function get_project_json(){
        $path = PATH_WEBROOT."/static/assets/webpack-assets.json";
        if(!is_file($path)) die("not found webpack-assets.json");
        $json = json_decode(file_get_contents($path),1);
        return $json;
    }
    function view_index($app){
        if(PtConfig::$env == "production") die("no access in production");
        $projects = self::get_project_apps();
        $is_wechat = Utils::is_wechat_browser();
        $env = PtConfig::$env;
        //项目列表
        if(empty($app)){
            $is_wechat = Utils::is_wechat_browser();
            $openid = null;
            if(empty($_SESSION['projects_wx_openid'])) {
                $auth_info = null;
                if($is_wechat){
                    $auth_info = Model_Wechat_Api::get_auth_info();
                }else{
                    if(!empty(Model_Wechat_Api::$config_open['appid'])){
                        $auth_info = Model_Wechat_Api::get_auth_info();
                    }
                }
                if(!empty($auth_info['openid'])){
                    $_SESSION['projects_wx_info'] = json_encode($auth_info);
                    $_SESSION['projects_wx_openid'] = $auth_info['openid'];
                }
            }else{
                $auth_info = isset($_SESSION['projects_wx_info'])?$_SESSION['projects_wx_info']:null;;
            }

            include PATH_APP."/src/View/Project/Index.php";
        }elseif(isset($projects[$app])){
            if(!empty($_GET['pd'])){
                self::handle_product_render($app);
            }else{
                $project = $projects[$app];
                $site_title = empty($project['site_title'])?"project":$project['site_title'];
                $webpack_domain = "/webpack";
                $vendor_js_url = "$webpack_domain/vendor.js";
                $app_css_url = null;
                $app_js_url = "$webpack_domain/$app.js";
                $antd_js_url = null;
                if($app == "manage"){
                    $antd_js_url = "$webpack_domain/antd.js";
                }
                if($app == "mission"){
                    include PATH_APP."/src/View/Mission/Index.php";exit;
                }
                if($app == "manage"){
                    include PATH_APP."/src/View/Manage/Index.php";exit;
                }
                include PATH_APP."/src/View/Project/App.php";
            }
        }
    }

    static function handle_product_render($app){
        $projects = self::get_project_apps();
        $project = $projects[$app];
        $site_title = empty($project['site_title'])?"project":$project['site_title'];
        $json = self::get_project_json();
        if(!isset($json[$app])) die("no foud app in webpack-assets.json");
        $app_css_url = "/static/assets/".$json[$app]['css'];
        $vendor_js_url = "/static/assets/".$json['vendor']['js'];
        $app_js_url = "/static/assets/".$json[$app]['js'];
        $antd_js_url = null;
        if($app == "manage"){
            $antd_js_url = isset($json['antd']) ? "/static/assets/".$json['antd']['js'] : null;
        }

        if($app == "mission"){
            include PATH_APP."/src/View/Mission/Index.php";exit;
        }
        if($app == "manage"){
            include PATH_APP."/src/View/Manage/Index.php";exit;
        }
        include PATH_APP."/src/View/Project/App.php";
    }
    function view_product($app){
        self::handle_product_render($app);
    }
    function action_env(){
        return array(
            'env'=>PtConfig::$env,
            "debug"=>\PtPHP\Database::$config['default']['type']
        );
    }
}
