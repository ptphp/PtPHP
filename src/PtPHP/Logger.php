<?php
/**
 * @link http://www.ptphp.com
 * @copyright Copyright (c) 2012 PtPHP Software LLC
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author joseph <ptphp@qq.com>
 */

namespace PtPHP;
use stdClass;
use Exception;
class Logger{
    private static $level = 0;
    private static $logs = array();
    private static $level_map = array(
        1 => 'FATAL',
        2 => 'ERROR',
        3 => 'WARN',
        4 => 'INFO',
        5 => 'DEBUG',
        6 => 'TRACE',
        7 => 'ALL',
    );
    private static $max_level = 7;
    private static $config;

    public function __construct(){
        throw new Exception("Static class");
    }
    static function print_config(){
        print_r(self::$config);
    }

    static function init($config=array()){
        if(!isset($config['level'])){
            self::$level = 0;
        }else if($config['level'] == '*' || strcasecmp($config['level'], 'all') === 0){
            self::$level = self::$max_level;
        }else{
            foreach(self::$level_map as $k=>$v){
                if($v === strtoupper($config['level'])){
                    self::$level = $k;
                    break;
                }
            }
        }

        self::$config = $config;
    }

    private static function write($level, $msg,$exception_point){
        if($level > self::$level){
            return;
        }
        if(is_array($msg) || is_object($msg)){
            if($level < 5){
                if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
                    $msg = json_encode($msg,JSON_UNESCAPED_UNICODE);
                }else{
                    $msg = Utils::unicodeString(json_encode($msg));
                }
            }else{ //debug 5 trace 6
                if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
                    $msg = Utils::unicodeString(json_encode($msg,JSON_PRETTY_PRINT));
                }else{
                    $msg = Utils::unicodeString(json_encode($msg));
                }
            }

        }
        $log = new stdClass();
        $log->time = microtime(true);
        $log->level = $level;
        $log->msg = $msg;
        self::write_file($log,$exception_point);
    }

    private static function write_file($log,$exception_point){
        $filename = null;
        foreach(self::$level_map as $level=>$name){
            if($log->level <= $level && isset(self::$config['files'][$name])){
                $filename = self::$config['files'][$name];
                break;
            }
        }
        if(strtolower(PHP_SAPI) == 'cli'){
            echo self::get_format_msg($log,$exception_point).PHP_EOL;
        }else{
            try{
                file_put_contents($filename, self::get_format_msg($log,$exception_point), FILE_APPEND);
            }catch(Exception $e){
                error_log($filename." is not exsits\n".self::get_format_msg($log),0);
            }
        }

    }
    static function get_format_msg($log,$exception_point){
        // TODO: client_ip
        $level = self::$level_map[$log->level];
        try{
            list($sec, $usec) = explode('.', $log->time);
            $usec = substr(sprintf('%03d', $usec), 0, 3);
            $time = date("Y-m-d H:i:s.{$usec}", $sec);    
        }catch(Exception $e){
            $time = date("Y-m-d H:i:s.{$usec}", $log->time); 
        }
        
        $msg = $log->msg;

        if($log->level < 5){
            $msg = preg_replace('/[ \r\n\t]*\n[ \r\n\t]*/', '', $msg);
            $msg = preg_replace('/[ \r\n\t]+/', ' ', $msg);
        }else{
            $msg = "\n".$msg;
        }

        if(isset($_SERVER["HTTP_CLIENT_IP"]) && $_SERVER["HTTP_CLIENT_IP"]!='unknown'){
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        }else if(isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && $_SERVER["HTTP_X_FORWARDED_FOR"]!='unknown'){
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }else if(isset($_SERVER["REMOTE_ADDR"]) && $_SERVER["REMOTE_ADDR"]!='unknown'){
            $cip = $_SERVER["REMOTE_ADDR"];
        }else{
            $cip = "0.0.0.0";
        }
        if($exception_point){
            $file = $exception_point['file'];
            $c_line = $exception_point['line'];
        }else{
            $bt = debug_backtrace(false);
            $file = $bt[3]['file'];
            $c_line = $bt[3]['line'];
            if(substr($file,-strlen("PtPHP/libs/Model.php")) == 'PtPHP/libs/Model.php'){
                $file = $bt[4]['file'];
                $c_line = $bt[4]['line'];
            }
        }

        $cip = Utils::is_cli()?"":"[$cip] ";
        $c_file = basename(dirname(dirname($file)))."/".basename(dirname($file))."/".basename($file);
        $line = sprintf("%s [%-5s] %s[%s:%s] %s".PHP_EOL, $time, $level, $cip, $c_file, $c_line, $msg);
        return $line;
    }

    static function trace($msg,$exception_point = null){
        self::write(6, $msg,$exception_point);
    }

    static function debug($msg,$exception_point = null){
        self::write(5, $msg,$exception_point);
    }

    static function info($msg,$exception_point = null){
        self::write(4, $msg,$exception_point);
    }

    static function warn($msg,$exception_point = null){
        self::write(3, $msg,$exception_point);
    }

    static function error($msg,$exception_point = null){
        self::write(2, $msg,$exception_point);
    }

    static function fatal($msg,$exception_point = null){
        self::write(1, $msg,$exception_point);
    }
}