<?php
/**
 * 初始化
 *
 */
date_default_timezone_set('Asia/Shanghai');
error_reporting(0);
ini_set( 'display_errors', 'Off' );

define("PATH_PRO",realpath(__DIR__."/../"));
define("PATH_VENDOR",   PATH_PRO."/vendor");
//define("PATH_VENDOR",   PATH_APP."/vendor");


define("PATH_WEBROOT",   PATH_PRO."/webroot");

define("PATH_APP",__DIR__);
define("PATH_MODEL",   PATH_APP."/src/Model");
define("PATH_MODELS",   PATH_APP."/src/Models");

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

