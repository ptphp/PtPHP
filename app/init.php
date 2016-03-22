<?php
/**
 * 初始化
 *
 */

if(PHP_SAPI != 'cli'){
}

define("PATH_APP",__DIR__);
define("PATH_PRO",realpath(__DIR__."/../"));
define("PATH_MODEL",   PATH_APP."/model");
define("PATH_CONTROLLER",   PATH_APP."/controller");

define("PATH_TESTS",   PATH_PRO."/Tests");
define("PATH_LIBS",   PATH_APP."/libs");
define("PATH_VENDOR",   PATH_PRO."/vendor");

//define("LocalPtphpAutoloadPath",'/data/projects/ptphp/src/autoload.php');
//if(is_file(LocalPtphpAutoloadPath)) include_once LocalPtphpAutoloadPath;
if(!is_file(PATH_VENDOR."/autoload.php")){
    throw new Exception("没有安装 composer");
}else{
    include PATH_VENDOR."/autoload.php";
}
include __DIR__."/common/autoload.php";
include __DIR__."/common/common.php";
include __DIR__."/config/setting.php";

