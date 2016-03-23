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
header('Access-Control-Allow-Headers:x-requested-with,content-type');

PtPHP\Logger::init(array(
    'level' => 'DEBUG', // none/off|(LEVEL)
    'files' => array( // ALL|(LEVEL)
        'ALL'	=> PATH_PRO.'/logs/'.date("Y-m-d").'.log',
    ),
));
