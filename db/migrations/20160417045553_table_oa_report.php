<?php

use Phinx\Migration\AbstractMigration;

class TableOaReport extends AbstractMigration
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
        $table = $this->table('oa_report',array('id' => false,"primary_key"=>"ret_id"));
        $table
            ->addColumn('ret_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('sno', 'string', array(
                'comment' => "编号",
                'null' => false,
                "limit"=>50
            ))
            ->addColumn('type', 'enum', array(
                "null"=>false,
                'values' => array("日报","周报")
            ))
            ->addColumn('done', 'text', array(
                'comment' => "已完成",
                'null' => false,
            ))
            ->addColumn('todo', 'text', array(
                'comment' => "计划",
                'null' => true,
            ))
            ->addColumn('report_date', 'date', array(
                'comment' => "报告日期",
                "null"=>true
            ))
            ->addColumn('copyer', 'string', array(
                'limit' => 100,
                "null"=>true,
                'comment' => "抄送"
            ))
            ->addColumn('comments', 'text', array(
                "null"=>true,
                'comment' => "评论"
            ))
            ->addColumn('clients', 'text', array(
                "null"=>true,
                'comment' => "客户"
            ))
            ->addColumn('add_time', 'datetime', array(
                'comment' => "填写时间",
                "null"=>false
            ))
            ->addColumn('from_uid', 'integer', array(
                'signed' => false,
                'comment' => "发起人",
                "null"=>false
            ))
            ->create();
    }
}
