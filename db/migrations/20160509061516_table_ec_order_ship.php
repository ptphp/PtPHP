<?php

use Phinx\Migration\AbstractMigration;

class TableEcOrderShip extends AbstractMigration
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
        $table = $this->table('ec_order_ship',array('id' => false));
        $table
            ->addColumn('ord_id', 'integer', array(
                'signed' => false,
            ))
            ->addColumn('province', 'string', array(
                'comment' => "省",
                'limit' => 10,
            ))
            ->addColumn('city', 'string', array(
                'comment' => "市",
                'limit' => 10,
            ))
            ->addColumn('district', 'string', array(
                'comment' => "区",
                'limit' => 10,
            ))
            ->addColumn('addr', 'string', array(
                'comment' => "地址",
                'limit' => 200,
            ))
            ->addColumn('phone', 'string', array(
                'comment' => "联系电话",
                'limit' => 25,
            ))
            ->addColumn('contact', 'string', array(
                'comment' => "联系人",
                'limit' => 10,
            ))
            ->addColumn('ship_status', 'integer',array(
                "comment"=>"订单状态 1 未发货; 2 已发货 3 已退货",
                "limit"=>11,
                "null"=>false,
                "default"=>1,
            ))
            ->addColumn('ship_no', 'string', array(
                'comment' => "发货单号",
                'limit' => 32,
            ))
            ->addColumn('return_no', 'string', array(
                'comment' => "退货货单号",
                'limit' => 32,
            ))
            ->addColumn('ship_time', 'datetime', array(
                'comment' => "发货时间",
                "null"=>true
            ))
            ->addColumn('return_time', 'datetime', array(
                'comment' => "退货时间",
                "null"=>true
            ))
            ->addIndex(array("ord_id"),array('unique' => true))
            ->create();
    }
}
