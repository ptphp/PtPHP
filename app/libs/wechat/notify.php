<?php
/**
 * Created by PhpStorm.
 * User: liaomei
 * Date: 2015/8/28
 * Time: 14:20
 */
require_once PATH_LIBS."/wechat/lib/WxPay.Api.php";
require_once PATH_LIBS.'/wechat/lib/WxPay.Notify.php';


class PayNotifyCallBack extends WxPayNotify
{
    //重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {
        pt_debug($data,__METHOD__);
        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            return false;
        }
        //查询订单，判断订单真实性
        if($data['return_code'] != 'SUCCESS'){
           return false;
        }
        pt_debug("SUCCESS",__METHOD__);
        Model_Order_Pay_Wechat::notify_callback($data['out_trade_no'],$data['transaction_id'],$data['total_fee']);
        return true;
    }
}
