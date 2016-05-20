<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class TableCrmStoreInfo extends AbstractMigration
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
        $table = $this->table('crm_store_info',array('id' => false));
        $table
            ->addColumn('sto_id', 'integer', array(
                'signed' => false
            ))
            ->addColumn('years', 'integer', array(
                'comment' => "店铺年限",
                'null' => true,
                "limit"=>MysqlAdapter::INT_TINY
            ))
            ->addColumn('nums', 'integer', array(
                'comment' => "店面数",
                'null' => true,
                "limit"=>MysqlAdapter::INT_SMALL
            ))
            ->addColumn('type', 'char', array(
                'comment' => "0 综合店 1 专业店 2  其他",
                "limit"=>1,
                "null"=>true
            ))
            ->addColumn('staff_num', 'integer', array(
                'comment' => "员工人数",
                'null' => true,
                "limit"=>MysqlAdapter::INT_SMALL
            ))
            ->addColumn('store_area', 'decimal', array(
                'comment' => "面积",
                'null' => true,
                "precision"=>7,
                "scale"=>2,
            ))
            ->addColumn('month_earn', 'decimal', array(
                'comment' => "月营业额",
                'null' => true,
                "precision"=>12,
                "scale"=>2,
            ))
            ->addColumn('add_time', 'datetime', array(
                'comment' => "填写日期",
                "null"=>false
            ))
            ->addColumn('op_uid', 'integer', array(
                'signed' => false,
                'comment' => "填写人",
                "null"=>false
            ))
            ->addIndex(array("sto_id"), array('unique' => true))
            ->create();
    }
}
