<?php
namespace PtPHP;
class Utils
{
    static function print_pre($var){
        echo "<pre />";
        var_export($var);
        return true;
    }
}
