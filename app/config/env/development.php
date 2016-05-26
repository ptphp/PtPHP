<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/3/23
 * Time: 上午12:21
 */

error_reporting(E_ALL);
ini_set( 'display_errors', 'On' );

header('content-type:application:json;charset=utf8');
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST, GET');
header('Access-Control-Allow-Headers:x-requested-with,content-type,X-File-Name');

PtPHP\Logger::init(array(
    'level' => 'DEBUG', // none/off|(LEVEL)
    'files' => array( // ALL|(LEVEL)
        'ALL'	=> PATH_PRO.'/logs/'.date("Y-m-d").'.log',
    ),
));
PtPHP\PtRedis::$config = array(
    "default"=>array(
        "host"=>"127.0.0.1",
        "port"=>6379
    )
);

PtConfig::$safeLogin['username'] = "admin";
PtConfig::$safeLogin['password'] = "admin@2016";


Model_Wechat_Api::$config['appid'] = '';
Model_Wechat_Api::$config['appsecret'] = '';

Model_Wechat_Api::$config_open['appid'] = '';
Model_Wechat_Api::$config_open['appsecret'] = '';

Model_Tools_Yunpian::$apikey = "49ab7d33006f4a0f2abbf4f681f46811";
//a7100dec