<?php

function pt_autoload($classname)
{
    $path = "";
    if(defined("PATH_MODEL") && substr($classname,0,6) == "Model_"){
        $path = PATH_MODEL . "/" . str_replace("_", "/", strtolower(substr($classname,6))) . ".php";
    }
    if(defined("PATH_CONTROLLER") && substr($classname,0,11) == "Controller_"){
        $path = PATH_CONTROLLER . "/" . str_replace("_", "/", strtolower(substr($classname,11))) . ".php";
    }
    if($path && is_file($path)){
        require_once($path);
    }
}

spl_autoload_register('pt_autoload');

