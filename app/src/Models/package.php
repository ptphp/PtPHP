<?php
/**
 * 套餐
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/2/1
 * Time: 下午5:26
 */

use PtPHP\Model as Model;

class Model_Package extends Model{
    const TABLE ="dian_package_data";
    const CACHE_KEY = "ldt_pkg_";

    static function set_pkg_data_cache($id,$data = array()){
        if(empty($data)){
            $table = self::TABLE;
            $data = self::_db()->select_row("select * from $table where id = ?",$id);
        }
        self::_redis()->set(self::CACHE_KEY.$id,json_encode($data));
        return $data;
    }
    static function get_pkg_data($id){
        $data = self::_redis()->get(self::CACHE_KEY.$id);
        if(empty($data)){
            $data = self::set_pkg_data_cache($id);
        }else{
            $data = json_decode($data,1);
        }
        return $data;
    }

    /**
     * @param $pay_type          支付方式
     * @param $month_fee         每月电费量
     * @param $package_data_id   套餐id
     * @param $ticket_id         绿电券id
     */
    static function confirm($pay_type,$month_fee,$package_data_id,$ticket_id){
        $ticket_price    = 0;
        if(empty($package_data_id)) _throw("请选择绿电套餐");

        //套餐
        $packageData = self::get_pkg_data($package_data_id);
        if(empty($packageData)) _throw('未检索到绿电套餐');
        if (empty($month_fee) || !is_numeric($month_fee)) _throw('请填写每月预计电费金额');
        if($packageData['period'] == 3){
            if($month_fee < 200) $month_fee = 200;
        }else{
            if($month_fee < C('PKG_PAGE_COMBO_NUM_INIT')) $month_fee = C('PKG_PAGE_COMBO_NUM_INIT');
        }
        //根据套餐配置的"是否允许使用优惠券"列来动态判断
        if(!$packageData['allow_coupon']) $ticket_id = 0;
    }
    /**
     * 套餐订单确认页
     */
    public function confirm1(){
        //支付方式
        $payType = I('payType', 0);



        if($_POST){
            $this->check_city();
            $this->assign("jumpUrl", U("Package/index"));
            $month_fee       = I('post.month_fee');        //每月电费量
            $package_data_id = I('post.package_data_id');  //套餐id
            $ticket_id       = I('post.ticket_id');        //绿电券id
            $ticket_price    = 0;
            if (empty($package_data_id) || !is_numeric($package_data_id)) $this->error('请选择绿电套餐');
            //套餐
            $packageData = $this->packageDataModel->find($package_data_id);
            if(empty($packageData)) $this->error('未检索到绿电套餐');
            if (empty($month_fee) || !is_numeric($month_fee)) $this->error('请填写每月预计电费金额');
            if($packageData['period'] == 3){
                if($month_fee < 200) $month_fee = 200;
            }else{
                if($month_fee < C('PKG_PAGE_COMBO_NUM_INIT')) $month_fee = C('PKG_PAGE_COMBO_NUM_INIT');
            }
            //根据套餐配置的"是否允许使用优惠券"列来动态判断
            if(!$packageData['allow_coupon']) $ticket_id = 0;

            //验证绿电券
            if(!empty($ticket_id)){
                $ticket = $this->ticketModel->disableFieldAutoFilters()->where("`inventory_id` = '".$ticket_id."' AND `user_id` = '".$this->uid."' AND `status` = '1' AND `is_used` = '0'")->find();
                if(empty($ticket))  $this->error('未检索到您选择的绿电券');
                $ticket_price = $ticket['coupon_amount'];

                //检查绿电券金额的合法性
                if (! $this->packageOrderModel->isOrderAmountValid($ticket, $ticket_price, $month_fee * $packageData['period'])) {
                    $this->error('这次的订单金额小于您选择的绿电券需要的最小订单金额');
                }
            }
            $amount = $month_fee * $packageData['period']; //订单总额
            if($amount < $packageData['price'])  $this->error('金额错误');
            $quantity = intval($amount/$packageData['price']);  //购买数量
            if($quantity <= 0 || $quantity > 6000) $this->error('购买数量错误');
            $amount = $amount - $ticket_price;
            $order_id = self::getOrderNumber(1, 'ES');

            $city_name = empty($_COOKIE['city_name']) ? "":urldecode($_COOKIE['city_name']);
            $city_code = empty($_COOKIE['city_code']) ? "":$_COOKIE['city_code'];

            $orderData = array(
                'order_id'        => $order_id,                              //'套餐订单号',
                'user_id'         => $this->uid,                             //'用户id',
                'order_title'     => $packageData['pro_name'],               //订单名称
                'pro_id'          => $packageData['pro_id'],                 //'产品id',
                'ticket_id'       => $ticket_id,                             //'红包id',
                'ticket_price'    => $ticket_price,                          //'红包ine
                'price'           => $packageData['price'],                  //'产品单价',
                'quantity'        => $quantity,                              //'购买数量',
                'holddays'        => $packageData['holddays'],               //锁定期
                'period'          => $packageData['period'],                 //'套餐周期(月/单位)',
                'month_fee'       => $month_fee,                             //'每月电费',
                'residue_period'  => $packageData['period'],                 //'剩余周期',
                'favorable'       => $packageData['favorable'],              //'优惠',
                'apr'             => $packageData['apr'],                    //'年化利率',
                'transfer_fee_ratio' => $packageData['transfer_fee_ratio'],  //'转让费率',
                'product_type'    => $packageData['product_type'],           //'绿能宝产品类型',
                'amount'          => $amount,                                //'订单总金额',
                'order_status'    => OT_WAIT,
                'affair_status'   => 0,                                      //状态(0:默认, 1:快钱支付成功(改订单状态), 2:调绿能宝支付成功接口状态, 3:向保理账户打款状态, 4:向绿电余额打本月金额状态, 5:记流水)',
                'addtime'         => $this->currentTime,                     //'下单时间',
                'pay_type'        => I('payType', 0) == 1 ? 'wepay' : '99bill',//'支付类型',
                'city_name'       => $city_name,                            //'城市名',
                'city_code'       => $city_code,                            //'城市代码',
                'ip2long'         => get_client_ip(1),                      //IP(long型),
                'ip'              => get_client_ip(0),                      //IP,
            );

            //设置套餐订单信息
            $orderData = $this->packageDataModel->setPackageOrderInfo($month_fee, $quantity, $ticket_price, $orderData, $packageData);

            $this->packageOrderModel->startTrans(); //开启事务
            $ret = $this->packageOrderModel->add($orderData);
            if($ret){
                //调绿能宝创建订单接口
                //绿电通订单类型: 0=新品中心，1交易中心(活期中心)
                if ($packageData['product_type'] == PackageDataModel::PRODUCT_TYPE_NEW) {
                    $order_type = 0;
                }
                if ($packageData['product_type'] == PackageDataModel::PRODUCT_TYPE_CURRENT) {
                    $order_type = 1;
                }
                //绿能宝支付方式
                $paypal_type = 102;
                //微信支付
                if ($payType == 1) {
                    $paypal_type = 103;
                }
                $create_order_param = array(
                    'order_type'    => $order_type, //绿电通订单类型: 0=新品中心，1交易中心(活期中心)
                    'paypal_type'   => $paypal_type,//支付方式前缀10，101绿电支付、102快捷支付、103微信支付
                    'userid'        => $this->uid,
                    'source'        => 6,          //6绿电通
                    'pro_id'        => $packageData['pro_id'],
                    'number'        => $quantity,
                    'coupon_price'  => $ticket_price,
                    'combo_days'    => $packageData['holddays'],
                    'direct_type'   => 2   //快捷
                );
                if ($order_type == 1) {
                    $create_order_param['yhsy'] = $packageData['apr'];
                    $create_order_param['transfer_fee'] = 0;
                    $create_order_param['transfer_discountrate'] = 0;
                }
                $lnb_order = spi_api(
                    'Order.createOrder',
                    $create_order_param,
                    'order'
                );
                if($lnb_order['status'] != 200){
                    $this->packageOrderModel->rollback();  //事务回滚
                    writelog(__FUNCTION__.':调绿能宝创建订单接口失败:orderid:[-'.$order_id.'-][->'.serialize($lnb_order).'<-]');
                    $this->error($lnb_order['msg']);
                }else{
                    $this->packageOrderModel->commit();  //事务提交
                    //回写绿能宝订单号
                    $this->packageOrderModel->where("`order_id` = '".$order_id."'")->save(array("lnb_order_id"=> $lnb_order['data']['order_id']));
                    //设置绿电券为已使用
                    if(!empty($ticket_id)){
                        $this->ticketModel->where("`inventory_id` = " . $ticket_id)->save(array('is_used' => '1', 'use_time' => $this->currentTime));
                    }
                    $this->redirect('Package/confirm?order_id='.$order_id.' &payType='.$payType);
                }
            }else{
                writelog(__FUNCTION__.':生成订单失败:sql:[-'.$this->packageOrderModel->getlastsql().'-]');
                $this->error('生成订单失败1');
            }
        }
        $this->assign("jumpUrl", U("Package/index"));
        $order_id = I('get.order_id');
        if(empty($order_id)) $this->redirect(U("Package/index"));
        $order = $this->packageOrderModel->getUseOrder($this->uid, $order_id);
        if(empty($order))  $this->error('未检索到订单');
        //判断订单是否待支付 或支付失败状态
        if($order['order_status'] != 0 && $order['order_status'] != 2) $this->error('此订单非待支付状态');
        if($payType==1){

            Vendor('Weichat.weixin');

            $weixin = new \Weixin();

            $this->assign('payBtn',$weixin->getPayBtn($order['order_id'],'购买绿电套餐',$order['amount'],C('WEIXIN_PAY_NOTIFY_URL_PACKAGE'),C('WEIXIN_PAY_SUCCESS_URL_PACKAGE')));

            $this->assign('order', $order);

            $this->display('weixin_confirm');

        }else{
            $this->assign('mobile',$this->bindinfo['mobile']);
            $this->assign('order', $order);
            $this->display();
        }

    }
}