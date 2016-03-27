<?php

function pt_autoload($class)
{
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ . '/../src/' . $path . '.php';
    if (file_exists($file)) {
        require_once $file;
    }else{
        if(defined("PATH_MODEL") && substr($class,0,6) == "Model_"){
            require_once PATH_MODEL . "/" . str_replace("_", "/", strtolower(substr($class,6))) . ".php";
        }
    }
}

spl_autoload_register('pt_autoload');
