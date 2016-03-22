<?php
namespace PtPHP;
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/3/22
 * Time: 下午8:03
 */
function print_pre($var){
    echo "<pre />";
    var_export($var);
    return true;
}