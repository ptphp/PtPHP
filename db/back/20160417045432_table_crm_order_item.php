<?php

use Phinx\Migration\AbstractMigration;

class TableCrmOrderItem extends AbstractMigration
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
        $table = $this->table('crm_order_item');
        $table
            ->addColumn('ord_id', 'integer', array(
                'signed' => false
            ))
            ->addColumn('item_id', 'integer', array(
                'signed' => false,
                'null'=>false
            ))
            ->addColumn('item_name', 'string', array(
                'comment' => "项目名",
                'null' => false,
                "limit"=>50
            ))
            ->addColumn('item_amount', 'decimal', array(
                'comment' => "项目单价",
                'null' => false,
                "default"=>0.0,
                "precision"=>12,
                "scale"=>1,
            ))
            ->addColumn('item_num', 'integer', array(
                'signed' => false,
                'null'=>false,
                'limit'=>\Phinx\Db\Adapter\MysqlAdapter::INT_TINY,
                'default'=>1
            ))
            ->addColumn('item_type', 'string', array(
                'comment' => "产品类型",
                'null' => false,
                "limit"=>50
            ))
            ->addColumn('item_unit', 'string', array(
                'comment' => "产品单位",
                'null' => false,
                "limit"=>50
            ))
            ->addColumn('doct_uid', 'integer', array(
                'signed' => false,
                'comment' => "预约医生uid",
                "null"=>true
            ))
            ->addColumn('appoint_time', 'datetime', array(
                'comment' => "预约时间",
                "null"=>true
            ))
            ->addColumn('is_give', 'boolean', array(
                'comment' => "是否赠送项目 0 非赠送 ; 1 赠送",
                'signed' => false,
                "null"=>false,
                'default'=>"0"
            ))
            ->addColumn('real_ys_uid', 'integer', array(
                'signed' => false,
                'comment' => "实际手术医生",
                "null"=>true
            ))
            ->addColumn('real_time', 'datetime', array(
                'comment' => "实际手术时间",
                "null"=>true
            ))
            ->addColumn('yl_note', 'string', array(
                'comment' => "备注",
                'null' => true,
                "limit"=>50
            ))
            ->create();
    }
}
