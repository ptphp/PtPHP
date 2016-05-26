<?php
use PtPHP\Model as Model;
use PtPHP\Curl as Curl;

class Model_Tools_Yunpian extends Model
{
    static $apikey = null;
    static $tpl_captcha = "【PtPHP】您的验证码为:%s";

    /**
     * 云片发送手机验证码
     * @param $mobile
     * @param $captcha
     * @return mixed
     * @throws AppException
     * @throws Exception
     */
    static function send_captcha($mobile,$captcha)
    {

        if(!self::$apikey) _throw("YUNPIN APIKEY IS NULL");
        $curl = new Curl();
        try {
            $url = "https://sms.yunpian.com/v1/sms/send.json";
            $data['apikey'] = self::$apikey;
            $data['mobile'] = $mobile;
            $data['text'] = sprintf(self::$tpl_captcha,$captcha);
            $res = $curl->post($url,$data);
            $body = json_decode($res['body']);
            if($body->code > 0){
                _throw($body->msg." ".$body->detail);
            }
            return $body;

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

}