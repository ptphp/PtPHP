<?php
use Symfony\Component\Yaml\Yaml;
use PtPHP\Utils as Utils;
/**
 * 配置类代码
 *
 */
class PtConfig{
    public static $env = "development";
    public static $qiniu = array(
        "access_key"=>"zlbOjuyGIUaq73PhpZVetqvcPIPk6EgugFHY3N-y",
        "secret_key"=>"7uiio8iIRfqOtlYqGpZpp7G3IpyUVOO5-QPkWkja",
        "bucket"=>"lvdiantong",
        "domain"=>"7xq9wj.com1.z0.glb.clouddn.com"
    );
    public static $userRsaAuth = true;
    public static $safeLogin = array(
        "username"=>"",
        "password"=>"",
    );
    public static $siteAdminTitle = "PtPHP";
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
            'prefix'=>$db_config['table_prefix'],
        )
    );
}

$env_setting_path = __DIR__."/env/".PtConfig::$env.".php";
if(is_file($env_setting_path)) require_once $env_setting_path;
