<?php

use Phinx\Migration\AbstractMigration;

class TableEcOrderPay extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('ec_order_pay',array('id' => false));
        $table
            ->addColumn('ord_id', 'integer', array(
                'signed' => false,
            ))
            ->addColumn('pay_price', 'decimal', array(
                'comment' => "支付金额",
                'null' => false,
                "default"=>0.0,
                "precision"=>12,
                "scale"=>1,
            ))
            ->addColumn('pay_blc_price', 'decimal', array(
                'comment' => "余额支付金额",
                'null' => false,
                "default"=>0.0,
                "precision"=>12,
                "scale"=>1,
            ))
            ->addColumn('pay_type', 'integer', array(
                'signed' => false,
                'comment' => "1 wechat_app; 2 wechat_i;n 3 alipay_app; 4 aliapy_web; 5 balance; 6 min; 7 else",
                'limit' => 10,
            ))
            ->addColumn('pay_status', 'integer', array(
                'signed' => false,
                'comment' => "1 未支付 2 已支付 3 已退款",
                'limit' => 10,
            ))
            ->addColumn('pay_time', 'datetime', array(
                'comment' => "支付时间",
                "null"=>true
            ))
            ->addColumn('refund_time', 'datetime', array(
                'comment' => "退款时间",
                "null"=>true
            ))
            ->addIndex(array("ord_id"),array('unique' => true))
            ->create();
    }
}
