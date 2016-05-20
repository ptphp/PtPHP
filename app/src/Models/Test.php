<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/3/23
 * Time: 下午5:00
 */
namespace Model;
use PtPHP\Model as Model;
class Test extends Model{
    function test(){
        self::_debug(1);
    }
}