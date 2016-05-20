<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class TableCrmTrace extends AbstractMigration
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
        $table = $this->table('crm_trace',array('id' => false,"primary_key"=>"trc_id"));
        $table
            ->addColumn('trc_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('note', 'text', array(
                'comment' => "备注",
                'null' => false,
            ))
            ->addColumn('rel_id', 'integer', array(
                'comment' => "关联记录ID",
                'signed' => false,
                "null"=>false
            ))
            ->addColumn('type', 'char', array(
                'comment' => "类型: 1 agent;2 store;3 client ",
                "limit"=>1,
                "null"=>true
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
            ->addColumn('ip', 'string', array(
                'comment' => "ip",
                "limit"=>15,
                "null"=>true
            ))
            ->create();
    }
}
