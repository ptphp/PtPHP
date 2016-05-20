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


Model_Wechat_Api::$config['appid'] = 'wxa19b2bb098f2de68';
Model_Wechat_Api::$config['appsecret'] = '3c75f718d59de9403672304d8b0d94ff';

Model_Wechat_Api::$config_open['appid'] = 'wx2d4f1d68e2980776';
Model_Wechat_Api::$config_open['appsecret'] = 'd4624c36b6795d1d99dcf0547af5443d';
