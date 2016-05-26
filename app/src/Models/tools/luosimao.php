<?php
use PtPHP\Model as Model;
use PtPHP\Curl as Curl;

class Model_Tools_Luosimao extends Model
{
    static $apikey = null;

    static function send_captcha($mobile,$captcha)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://sms-api.luosimao.com/v1/send.json");

        curl_setopt($ch, CURLOPT_HTTP_VERSION  , CURL_HTTP_VERSION_1_0 );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPAUTH , CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD  , 'api:key-4148ee39dbfaffbba7730ef32f736372');

        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('mobile' => $mobile,'message' => '验证码:12345【PtApp】'));

        $res = curl_exec( $ch );
        curl_close( $ch );
        //$res  = curl_error( $ch );
        var_dump($res);
    }

}