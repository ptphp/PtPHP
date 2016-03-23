<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/3/23
 * Time: 下午5:00
 */
namespace Controller\Test;
use PtPHP\Model as Model;
class Test extends Model{
    static function action_test($id,$test){
        $res = self::_db()->row("select 1=?",1);
        $res = self::_db()->rows("select 1=?",1);
        var_export(self::_db()->get_last_sql());
        exit;
    }
}