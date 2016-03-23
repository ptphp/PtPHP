<?php
use PtPHP\Utils as Utils;
use PtPHP\Logger as Logger;

include_once __DIR__."/init.php";

$result = null;
$error_code = 0;
try {
    $controller = Utils::I("controller");
    if(!$controller) _throw("controller is null",9001);
    if(!preg_match("/^[a-zA-Z]+[a-zA-Z0-9\/]+?$/",$controller))
        _throw("controller: $controller is invalid",9001);
    $action = Utils::I("action");
    if(!$action) _throw("action is null",9002);
    if(!preg_match("/^[a-zA-Z]+[A-Za-z0-9_]+?$/",$action))
        _throw("action:".$action." is invalid",9001);
    $c_t = explode("/",$controller);
    $controller = "Controller";
    foreach($c_t as $i){
        if($i) $controller .= "\\".ucfirst(strtolower($i));
    }

    if(!class_exists($controller)) _throw($controller." is no exsits",9003);
    $action = "action_".strtolower($action);
    define("__NODE__",$controller."::".$action);
    if(1){
        $reflection = new ReflectionMethod($controller, $action);
        $fire_args = array();
        foreach($reflection->getParameters() AS $arg)
        {
            if(isset($_REQUEST[$arg->name]))
                $fire_args[$arg->name] = $_REQUEST[$arg->name];
            else
                $fire_args[$arg->name] = null;
        }
        $controller_obj = new $controller();
        $return = call_user_func_array(array($controller_obj, $action), $fire_args);
    }else{
        $controller_obj = new $controller();
        if(!method_exists($controller_obj,$action)) _throw($controller."::$action is no exsits",9004);
        //$return = $controller_obj->$action();
        $return = call_user_func_array(array($controller_obj, $action), array());
    }

    if($return !== null) $result = $return;

}catch(AppException $e){
    //print_r($exception_point);
    $error_code = ($e->getCode()) ? $e->getCode() : 1;
    $result = $e->getMessage();
    Logger::warn(array($error_code,$result),Utils::get_exception_file_line($e->getTrace()));

}catch(Exception $e){
    $error_code = ($e->getCode()) ? $e->getCode() : 1;
    $result = $e->getMessage();
    Logger::error(array($error_code,$result,$e->getTrace()));
}
api_json_response($result,$error_code);
