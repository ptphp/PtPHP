<?php

use Phinx\Migration\AbstractMigration;

class TableEcOrder extends AbstractMigration
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
        $table = $this->table('ec_order',array('id' => false,"primary_key"=>"ord_id"));
        $table
            ->addColumn('ord_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('user_id', 'integer', array(
                'signed' => false,
                'null'=>false
            ))
            ->addColumn('orderno', 'string', array(
                'comment' => "订单号",
                'null' => false,
                "limit"=>50
            ))
            ->addColumn('price_total', 'decimal', array(
                'comment' => "订单总计",
                'null' => false,
                "default"=>0.0,
                "precision"=>8,
                "scale"=>1,
            ))
            ->addColumn('price_exp', 'decimal', array(
                'comment' => "现金收费",
                'null' => false,
                "default"=>0.0,
                "precision"=>3,
                "scale"=>1,
            ))
            ->addColumn('tik_id', 'integer', array(
                'signed' => false,
                'null'=>true,
                'comment' => "抵用券 id",
            ))
            ->addColumn('price_tik', 'decimal', array(
                'comment' => "现金收费",
                'null' => false,
                "default"=>0.0,
                "precision"=>5,
                "scale"=>1,
            ))
            ->addColumn('status', 'integer',array(
                "comment"=>"订单状态 1 侍支付; 2 超时未支付系统取消;3 自己取消;4 已支付 ;5 已退款;6 已发货;7 已收货 ;8 已退货 9 已完成",
                "limit"=>11,
                "null"=>false,
                "default"=>1,
            ))
            ->addColumn('add_time', 'datetime', array(
                'comment' => "添加时间",
                "null"=>false
            ))
            ->addColumn('up_time', 'datetime', array(
                'comment' => "更新时间",
                "null"=>true
            ))
            ->addIndex(array("orderno"),array('unique' => true))
            ->create();
    }
}
