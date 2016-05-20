<?php

use Phinx\Migration\AbstractMigration;

class TableCrmProduct extends AbstractMigration
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
        $table = $this->table('crm_product',array('id' => false,"primary_key"=>"pro_id"));
        $table
            ->addColumn('pro_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('name', 'string', array(
                'comment' => "name",
                "limit"=>50,
                'null' => false,
            ))
            ->addColumn('sno', 'string', array(
                'comment' => "项目编号",
                "limit"=>32,
                'null' => false,
            ))
            ->addColumn('type', 'string', array(
                'comment' => "类别",
                "limit"=>20,
                'null' => false,
            ))
            ->addColumn('amount', 'decimal', array(
                'comment' => "金额",
                'null' => false,
                "default"=>0.0,
                "precision"=>10,
                "scale"=>1,
            ))
            ->addColumn('unit', 'string', array(
                'comment' => "单位",
                "limit"=>20,
                'null' => false,
            ))
            ->addColumn('add_time', 'datetime', array(
                'comment' => "填写日期",
                "null"=>false
            ))
            ->create();
    }
}
