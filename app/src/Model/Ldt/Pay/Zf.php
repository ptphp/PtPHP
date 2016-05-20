<?php
namespace Model\Ldt\Pay;
use PtPHP\Model as Model;
use PtPHP\Curl as Curl;
use PtPHP\Utils as Utils;
use PtPHP\HttpRequest as HttpRequest;

class Zf extends Model{
    static function getResponseBody(){
        /**
        "{\"webOrderId\":\"182605\",
         * \"inTradeOrderNo\":\"1458746828\",
         * \"weChatOrderNo\":\"4005402001201603234224282498\",
         * \"tradeStatus\":\"SUCCESS\",
         * \"gmtCreate\":\"20160323232707  \",
         * \"gmtPayment\":\"20160323232715\",
         * \"logonId\":\"o_0UuweXjFNLLM6Sn2Yyro0sh8QQ\",
         * \"totalFee\":\"1\",
         * \"signMsg\":\"C6EBA419806F84D19AEA05AA7C98F3C8\"}"
         */
        return HttpRequest::param_body();
    }

    static function checkCallBackSign($response){
        $response['webOrderId'];
        $key = "12345678";
        $_sign = strtoupper(md5($response['webOrderId'].$response['inTradeOrderNo'].$response['tradeStatus'].$key));
        return $response['signMsg'] == $_sign;
    }

    static function getPayUrl($orderno,$total,$subject,$http_host = ''){
        $payType = Utils::is_wechat_browser() ? 19 : 18 ;
        $host = empty($http_host) ? HTTP_HOST : $http_host;
        $host = rtrim($host,"/");
        $data = array();
        $data['merchantNo'] = "990290048160001";
        $data['terminalNo'] = "77700032";
        $data['payMoney'] = $total;
        $data['productName'] = $subject;
        $data['inTradeOrderNo'] = $orderno;
        $data['payType'] = $payType;
        $data['merchant_url'] = "$host/api/pay/zf/pam_callback.php";
        $data['call_back_url'] = "$host/api/pay/zf/callback.php";
        $data['notify_url'] = "$host/api/pay/zf/notifySanWing.php";
        $key = "12345678";
        $data['signMsg'] = strtoupper(md5($data['merchantNo'].$data['terminalNo'].$data['payMoney'].
            $data['inTradeOrderNo'].$data['productName'].$data['payType'].$key));
        $url = "http://paygw.sanwing.com/swPayInterface";
        $url .= $payType == 18 ? "/html/alipayapi.jsp":"/wechat/wechatPay.jsp";
        $url .= "?".http_build_query($data);
        return $url;
    }
}