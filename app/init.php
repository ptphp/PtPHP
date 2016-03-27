<?php
/**
 * 初始化
 *
 */
date_default_timezone_set('Asia/Shanghai');
error_reporting(0);
ini_set( 'display_errors', 'Off' );

ini_set('session.name', "jid0054");
#30天过期 单位:秒
ini_set('session.gc_maxlifetime', 2592000);
#过期后清除概率 gc_probability / gc_divisor : 1/1000
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 1000);


define("PATH_PRO",realpath(__DIR__."/../"));
define("PATH_APP",__DIR__);

define("PATH_VENDOR",   PATH_PRO."/vendor");
//define("PATH_VENDOR",   PATH_APP."/vendor");

define("PATH_MODEL",   PATH_APP."/src/Model");
define("PATH_CONTROLLER",   PATH_APP."/src/Controller");

define("PATH_TESTS",   PATH_PRO."/Tests");
define("PATH_LIBS",   PATH_APP."/libs");

if(!is_file(PATH_VENDOR."/autoload.php")){
    throw new Exception("没有安装 composer");
}else{
    include PATH_VENDOR."/autoload.php";
}
include __DIR__."/common/autoload.php";
include __DIR__."/common/common.php";
include __DIR__."/config/setting.php";

