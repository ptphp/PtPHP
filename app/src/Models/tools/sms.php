<?php
use PtPHP\Model as Model;
use PtPHP\Curl as Curl;
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 7/10/15
 * Time: 5:49 PM
 */
class Model_Tools_Sms extends Model
{
    static $apikey = null;
    static function send($mobile,$content)
    {
        try {
            $username = "gercells";
            $key = "32215d41412320e5315b";
            $curl = new Curl();
            $url = "http://utf8.sms.webchinese.cn/?Uid={$username}&Key={$key}&smsMob=$mobile&smsText=" . urlencode($content);
            $res = $curl->get($url);
            $body = $res['body'];
            self::_debug($mobile);
            self::_debug($content);
            self::_debug($body);
            return $body;
        } catch (Exception $e) {
            _throw($e->getMessage());
        }

    }
    static function send_by_tpl($mobile,$data)
    {
        if(!self::$apikey) _throw("YUNPIN APIKEY IS NULL");
        $curl = new Curl();
        try {
            $url = "http://yunpian.com/v1/sms/tpl_send.json";
            $data['apikey'] = self::$apikey;
            $data['mobile'] = $mobile;
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