<?php
use Symfony\Component\Yaml\Yaml;
/**
 * 配置类代码
 *
 */
class PtConfig{
    static $env = "development";
}
date_default_timezone_set('Asia/Shanghai');
error_reporting(0);
ini_set( 'display_errors', 'Off' );

$phinx_config = Yaml::parse(file_get_contents(PATH_PRO."/phinx.yml"));
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
PtPHP\PtRedis::$config = array(
    "default"=>array(
        "host"=>"127.0.0.1",
        "port"=>27017
    )
);


$env_setting_path = __DIR__."/env/".PtConfig::$env.".php";
if(is_file($env_setting_path)) require_once $env_setting_path;