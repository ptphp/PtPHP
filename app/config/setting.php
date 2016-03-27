<?php
use Symfony\Component\Yaml\Yaml;
use PtPHP\Utils as Utils;
/**
 * 配置类代码
 *
 */
class PtConfig{
    public static $env = "development";
    public static $userRsaAuth = "false";
}

$env = Utils::get_pt_env("APPLICATION_ENV");
if($env){
    PtConfig::$env = $env;
}else{
    if(is_file(__DIR__."/.env.php")) PtConfig::$env = require_once __DIR__."/.env.php";
}
$phinx_config = null;
if(is_file(PATH_PRO."/phinx.yml")){
    $phinx_config = Yaml::parse(@file_get_contents(PATH_PRO."/phinx.yml"));
}elseif(is_file(PATH_APP."/config/phinx.yml")){
    $phinx_config = Yaml::parse(@file_get_contents(PATH_APP."/config/phinx.yml"));
}
if($phinx_config){
    $db_config = $phinx_config['environments'][PtConfig::$env];
    PtPHP\Database::$config = array(
        'default'=>array(
            'type'=>'mysql',
            'host'=>$db_config['host'],
            'port'=>$db_config['port'],
            'dbname'=>$db_config['name'],
            'dbuser'=>$db_config['user'],
            'dbpass'=>$db_config['pass'],
            'charset'=>$db_config['charset'],
        )
    );
}

PtPHP\PtRedis::$config = array(
    "default"=>array(
        "host"=>"127.0.0.1",
        "port"=>6379
    )
);

$env_setting_path = __DIR__."/env/".PtConfig::$env.".php";
if(is_file($env_setting_path)) require_once $env_setting_path;