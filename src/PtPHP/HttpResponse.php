<?php
/**
 * @link http://www.ptphp.com
 * @copyright Copyright (c) 2012 PtPHP Software LLC
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author joseph <ptphp@qq.com>
 */

namespace PtPHP;

class HttpResponse {
    static function json_redirect($msg,$url,$status = 0){
        self::json_response("",$status,$msg,$url);
    }
    /**
     * json 异常 响应
     *
     * @param string $message   返回提示
     * @param int $status       状态值
     * @param string $redirect  跳转地址
     * @param array $return
     */
    function json_error($message,$status = 1,$redirect = '',$return = array(),$exception = array()){
        json_response($return,$status,$message,$redirect,$exception);
    }

    /**
     * json 成功 响应
     *
     * @param $return
     */
    function json_success($return){
        json_response($return);
    }

    /**
     * json 响应
     *
     * @param $return
     * @param string $message   返回提示
     * @param int $status       状态值
     * @param string $redirect  跳转地址
     * @param object debug      debug信息
     */
    function json_response($return,$status = 0,$message = '',$redirect = '',$exception = array()){
        if(is_cli()) return;
        $run_print = ob_get_clean();
        if(!\PtApp::$ob_flushed) header('Content-Type: application/json');
        $debug = array(
            "run_print"=>$run_print,
            "debug"=>get_run_debug()
        );
        $data = array(
            "return"=>$return,
            "message"=>$message,
            "redirect"=>$redirect,
            "status"=>$status,
        );
        if(local_dev()){
            $debug['debug']['app']['_SESSION'] = \PtApp::$session_started?$_SESSION:null;
            $data['exception'] = $exception;
            $data['debug_sql'] = $debug['debug']['sql'];
            $data['debug_app'] = $debug['debug']['app'];
        }
        echo json_encode($data);
        exit;
    }

}
