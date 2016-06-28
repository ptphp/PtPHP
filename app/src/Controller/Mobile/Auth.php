<?php
namespace Controller\Mobile;

use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
use Model_Session;
use Model_Auth;
use Model_Admin_Auth;
use PtConfig;



class Auth extends Model{
    function __construct()
    {
        Model_Session::session_start(true);
    }

    /**
     * 发起一个post请求到指定接口
     *
     * @param string $api 请求的接口
     * @param array $params post参数
     * @param int $timeout 超时时间
     * @return string 请求结果
     */
    function postRequest( $api, array $params = array(), $timeout = 30 ) {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $api );
        // 以返回的形式接收信息
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        // 设置为POST方式
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params ) );
        // 不验证https证书
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
            'Accept: application/json',
        ) );
        // 发送数据
        $response = curl_exec( $ch );
        // 不要忘记释放资源
        curl_close( $ch );
        return $response;
    }
    /**
     * 登陆
     * @return string
     */
    function action_login_by_mobile_captcha($mobile,$captcha,$zone){
        $api = 'https://webapi.sms.mob.com';
        $appkey = '1311d54a7bc9a';
        $response = $this->postRequest( $api . '/sms/verify', array(
            'appkey' => $appkey,
            'phone' => $mobile,
            'zone' =>  $zone,
            'code' => $captcha,
        ) );
        self::_debug($response);
        $response = json_decode($response,1);
        self::_debug($response);
        $user_info = null;
        if( $response['status'] != 200){
            $msg = self::get_error_msg($response['status']);
            self::_debug($msg);
            _throw($msg);
        }else {
            $table = self::_table("user");
            $user_info = self::_db()->row("select user_id,mobile from $table where mobile = ?", $mobile);
            if (!$user_info) {
                $user_info["user_id"] = self::_db()->insert($table, array(
                    "mobile" => $mobile,
                    "add_time" => Utils::date_time_now(),
                ));
                $user_info['mobile'] = $mobile;
            }
        }
        return array(
            "msg"=>"登陆成功",
            "user_info"=>$user_info,
            "token"=>session_id()
        );
    }
    static function get_error_msg($code){
        $error = array(
            405=>"AppKey为空",
            406=>"AppKey无效",
            456=>"国家代码或手机号码为空",
            457=>"手机号码格式错误",
            466=>"请求校验的验证码为空",
            467=>"请求校验验证码频繁（5分钟内同一个appkey的同一个号码最多只能校验三次）",
            468=>"验证码错误",
            474=>"没有打开服务端验证开关"
        );
        return isset($error[$code]) ? $error[$code]: "系统错误";
    }

}