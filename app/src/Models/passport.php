<?php

use PtPHP\Model as Model;
use PtPHP\Curl as Curl;

/**
 * Passport
 * Class Model_Passport
 * @author Joseph
 */
class Model_Passport extends Model{
    static $error_msg = array(
        "10001"=>"参数为空",
        "10002"=>"用户已经存在",
        "10003"=>"用户名或密码错误",
        "10004"=>"旧密码与新密码相同",
        "10005"=>"用户不存在",
        "10006"=>"验证码错误",
        "10007"=>"用户名格式不正确",
        "10008"=>"手机号格式不正确",
        "10009"=>"邮箱格式不正确",
        "90000"=>"未注册token，请联系用户中心技术注册",
        "90001"=>"未传递sign",
        "90002"=>"签名不一致，验签失败",
    );

    static function api($action,$query,$use_ip = false){
        $curl = new Curl();
        try {
            $url = self::get_url($action,$query,$use_ip);
            self::_debug($url);
            $response = $curl->get($url,array(
                //CURLOPT_VERBOSE=>1,
                CURLOPT_REFERER => PASSPORT_HOST
            ));
            //self::_debug($response);
        }catch(Exception $e){
            self::_error($e->getMessage());
            _throw("call passport api error");
        }
        return self::handle_response($response);
    }
    static function sign($param){
        ksort($param);
        $_sign = md5(PASSPORT_TOKEN.implode("",$param));
        return $_sign;
    }
    static function get_url($action,$query,$use_ip = false){
        if($use_ip)
            $query['ip']       = self::get_ip();
        $query['sign'] = self::sign($query);
        //$query['host'] = 'test.dian.solarbao.com';
        $url = PASSPORT_URL."/".$action."?".http_build_query($query);
        return $url;
    }
    static function handle_response($response){
        $response_body = $response['body'];
        $response_body = json_decode($response_body);
        $status = $response_body->status;
        self::_debug($response_body);
        if($status > 0){
            _throw(self::$error_msg[$status],$status);
        }
        return $response_body;
    }
    static function get_ip(){
        return PtPHP\Utils::ip(true);
    }
    static function is_mobile($mobile){
        return PtPHP\Utils::is_mobile($mobile);
    }
    static function is_email($email){
        return PtPHP\Utils::is_email($email);
    }
    /**
     * 注册接口
     * @param $username       必填
     * @param $password       必填
     * @param string $mobile  选填（单独手机号注册，用户名就是手机号）
     * @param string $email   选填（单独邮箱注册，用户名就是邮箱）
     * @return array
     * @throws Exception
     */
    static function regist($username,$password,$mobile = '',$email = ''){
        $query = array();
        if(!$username) throw new Exception("用户名不能为空");
        if(!$password) throw new Exception("密码不能为空");
        $query['username']   = $username;
        $query['password']   = $password;
        if($mobile){
            if(!self::is_mobile($mobile)) throw new Exception("手机号不合法");
            $query['mobile'] = $mobile;
        }
        if($email){
            if(!self::is_email($email)) throw new Exception("邮箱不合法");
            $query['mobile'] = $email;
        }

        $response = self::api("regist",$query,true);
        return array(
            "userid"  => $response->data->userid,
            "username"=> $response->data->username,
        );
    }

    /**
     * 登陆
     * @param $username  用户名  必填
     * @param $password  明文    必填
     * @return array
     * @throws Exception
     */
    static function login($username,$password){
        $query = array();
        $query['username'] = $username;
        $query['password'] = $password;
        $response = self::api("login",$query,true);
        return array(
            "userid"   =>$response->data->userid,
            "username" =>$response->data->username,
            "locked"   =>$response->data->locked //账户是否已锁定Y：锁定 N：未锁定
        );
    }

    /**
     * 修改密码
     * @param $userid
     * @param $password
     * @param $newpassword
     * @return array
     * @throws Exception
     */
    static function modify_password($userid,$password,$newpassword){
        $query = array();
        $query['userid']      = $userid;
        $query['password']    = $password;
        $query['newpassword'] = $newpassword;
        $response = self::api("modifypassword",$query,true);
        return array(
            "userid"   => $response->data->userid,
            "username" => $response->data->username,
        );
    }

    /**
     * 重置密码
     * @param $username
     * @return array
     * @throws Exception
     */
    static function reset_password($username){
        $query = array();
        $query['username']      = $username;
        $response = self::api("resetpassword",$query,true);
        return array(
            "userid"   => $response->data->userid,
            "password" => $response->data->password,
        );
    }

    /**
     * 判断手机号是否存在
     * @param $mobile
     * @return array
     * @throws Exception
     */
    static function check_mobile($mobile){
        if(!self::is_mobile($mobile)) _throw("手机号不合法");
        $query = array();
        $query['mobile']      = $mobile;
        $response = self::api("checkmobile",$query);
        return array(
            "userid"   => $response->data->userid,
        );
    }

    /**
     * 判断邮箱是否存在
     * @param $email
     * @return array
     * @throws Exception
     */
    static function check_email($email){
        if(!self::is_email($email)) throw new Exception("email不合法");

        $query = array();
        $query['email']      = $email;
        $response = self::api("checkemail",$query);
        return array(
            "userid"   => $response->data->userid,
        );
    }

    /**
     * 判断用户名是否存在
     * @param $username  注册时用户名 （中英文、数字）
     * @return array
     * @throws Exception
     */
    static function check_usename($username){
        $query = array();
        $query['username']      = $username;
        $response = self::api("checkemail",$query);
        return array(
            "userid"   => $response->data->userid,
        );
    }

    /**
     *
     * @param $userid 获取用户信息
     * @return array
     * @throws Exception
     */
    static function get_user($userid){
        $query = array();
        $query['userid']      = $userid;
        $response = self::api("getuser",$query);
        self::_debug($response);
        return array(
            "userid"   => $response->data->user_id,
            "username"   => $response->data->username,
            "mobile"   => $response->data->mobile,
            "email"   => $response->data->email,
        );
    }
}