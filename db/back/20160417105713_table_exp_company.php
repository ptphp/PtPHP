<?php

use Phinx\Migration\AbstractMigration;

class TableExpCompany extends AbstractMigration
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
        //快递公司
        $table = $this->table('exp_company',array("id"=>false,"primary_key"=>"com_id"));
        $table
            ->addColumn('com_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('com_name', 'string', array(
                "null"=>false,
                "limit"=>"50"
            ))
            ->addColumn('com_code', 'string', array(
                "null"=>false,
                "limit"=>"50"
            ))
            ->addColumn('com_thumb', 'string', array(
                "null"=>false,
                "limit"=>"254"
            ))
            ->addColumn('status', 'boolean', array(
                "null"=>false,
                "signed"=>false,
                "default"=>1
            ))
            ->addColumn('add_time', 'datetime', array(
                'comment' => "添加时间",
                "null"=>false
            ))
            ->addColumn('op_uid', 'integer', array(
                'comment' => "填加人",
                "null"=>false,
                "signed"=>false
            ))
            ->create();
    }
}
