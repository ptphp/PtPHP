<?php

use Phinx\Migration\AbstractMigration;

class TableCrmOrder extends AbstractMigration
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
        $table = $this->table('crm_order',array('id' => false,"primary_key"=>"ord_id"));
        $table
            ->addColumn('ord_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('clt_id', 'integer', array(
                'signed' => false,
                'null'=>false
            ))
            ->addColumn('pord_id', 'integer', array(
                'signed' => false,
                'null'=>true
            ))
            ->addColumn('orderno', 'string', array(
                'comment' => "订单号",
                'null' => false,
                "limit"=>50
            ))
            ->addColumn('total', 'decimal', array(
                'comment' => "订单总计",
                'null' => false,
                "default"=>0.0,
                "precision"=>12,
                "scale"=>1,
            ))
            ->addColumn('total_pos', 'decimal', array(
                'comment' => "POS收费",
                'null' => false,
                "default"=>0.0,
                "precision"=>12,
                "scale"=>1,
            ))
            ->addColumn('total_cash', 'decimal', array(
                'comment' => "现金收费",
                'null' => false,
                "default"=>0.0,
                "precision"=>12,
                "scale"=>1,
            ))
            ->addColumn('give_amount', 'decimal', array(
                'comment' => "赠送价值合计",
                'null' => false,
                "default"=>0.0,
                "precision"=>12,
                "scale"=>1,
            ))
            ->addColumn('cw_uid', 'integer', array(
                'signed' => false,
                'comment' => "财务确认人",
                "null"=>true
            ))
            ->addColumn('cw_note', 'string', array(
                'limit' => 120,
                "null"=>true,
                'comment' => "财务备注"
            ))
            ->addColumn('cw_time', 'datetime', array(
                'comment' => "财务确认时间",
                "null"=>true
            ))
            ->addColumn('yl_uid', 'integer', array(
                'signed' => false,
                'comment' => "医疗确认人",
                "null"=>true
            ))
            ->addColumn('yl_note', 'string', array(
                'limit' => 120,
                "null"=>true,
                'comment' => "医疗确认备注"
            ))
            ->addColumn('yl_time', 'datetime', array(
                'comment' => "医疗确认时间",
                "null"=>true
            ))
            ->addColumn('add_time', 'datetime', array(
                'comment' => "填写日期",
                "null"=>false
            ))
            ->addColumn('op_uid', 'integer', array(
                'signed' => false,
                'comment' => "填写人",
            ))
            ->create();
    }
}
