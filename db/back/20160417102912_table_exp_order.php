<?php

use Phinx\Migration\AbstractMigration;

class TableExpOrder extends AbstractMigration
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
        //快递订单
        $table = $this->table('exp_order',array('id' => false,"primary_key"=>"ord_id"));
        $table
            ->addColumn('ord_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('user_id', 'integer', array(
                'signed' => false,
                "null"=>false,
            ))
            ->addColumn('dst_name', 'string', array(
                'comment' => "收件人姓名",
                "limit"=>50,
                'null' => false,
            ))
            ->addColumn('dst_phone', 'string', array(
                'comment' => "收件人手机或电话",
                "limit"=>50,
                'null' => false,
            ))
            ->addColumn('dst_prov', 'string', array(
                'comment' => "收件人省",
                "limit"=>10,
                'null' => false,
            ))
            ->addColumn('dst_city', 'string', array(
                'comment' => "收件人城市",
                "limit"=>10,
                'null' => false,
            ))
            ->addColumn('dst_dist', 'string', array(
                'comment' => "收件人区县",
                "limit"=>10,
                'null' => true,
            ))
            ->addColumn('dst_addr', 'string', array(
                'comment' => "收件人地址",
                "limit"=>200,
                'null' => false,
            ))
            ->addColumn('adr_name', 'string', array(
                'comment' => "寄件人姓名",
                "limit"=>50,
                'null' => false,
            ))
            ->addColumn('adr_phone', 'string', array(
                'comment' => "寄件人手机或电话",
                "limit"=>50,
                'null' => false,
            ))
            ->addColumn('adr_prov', 'string', array(
                'comment' => "寄件人省",
                "limit"=>10,
                'null' => false,
            ))
            ->addColumn('adr_city', 'string', array(
                'comment' => "寄件人市",
                "limit"=>10,
                'null' => false,
            ))
            ->addColumn('adr_dist', 'string', array(
                'comment' => "寄件人区",
                "limit"=>10,
                'null' => true,
            ))
            ->addColumn('adr_addr', 'string', array(
                'comment' => "寄件人地址",
                "limit"=>200,
                'null' => false,
            ))
            ->addColumn('store_id', 'integer', array(
                'comment' => "店铺id",
                'null' => true,
                "signed"=>false
            ))
            ->addColumn('com_id', 'integer', array(
                'comment' => "快递公司id",
                'null' => false,
                "signed"=>false
            ))
            ->addColumn('status', 'char', array(
                'limit'=>1,
                'comment' => "订单状态:0 未取,1 已取,2 已取消",
                'null' => false,
                "default"=>0
            ))
            ->addColumn('exp_type', 'boolean', array(
                'comment' => "类型:0 文件;1 包裹",
                'null' => false,
                'signed'=>false,
                "default"=>0
            ))
            ->addColumn('need_box', 'boolean', array(
                'comment' => "is_box",
                'null' => false,
                'signed'=>false,
                "default"=>0
            ))
            ->addColumn('from_wx', 'boolean', array(
                'comment' => "来自微信",
                'null' => false,
                'signed'=>false,
                "default"=>1
            ))
            ->addColumn('note', 'string', array(
                'comment' => "备注",
                "limit"=>200,
                'null' => true,
            ))
            ->addColumn('valid_time', 'string', array(
                'comment' => "取件时间",
                "limit"=>200,
                'null' => true,
            ))
            ->addColumn('add_time', 'datetime', array(
                'comment' => "填写时间",
                "null"=>false
            ))
            ->addColumn('up_time', 'datetime', array(
                'comment' => "更新时间",
                "null"=>true
            ))
            ->create();
    }
}
