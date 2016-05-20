<?php

use Phinx\Migration\AbstractMigration;

class TableOaApprove extends AbstractMigration
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
        $table = $this->table('oa_approve',array('id' => false,"primary_key"=>"apr_id"));
        $table
            ->addColumn('apr_id', 'integer', array(
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
                'values' => array("出差","报销","申购","请假","借款","返款","派车","特批")
            ))
            ->addColumn('status', 'char', array(
                'comment' => "状态 : 0 侍审批; 1 通过 ;2 驳回",
                'null' => false,
                'default'=>0,
                "limit"=>1
            ))
            ->addColumn('priority', 'integer', array(
                'comment' => "当前流程: 0 1 2 ...",
                'null' => false,
                'default'=>0,
                "limit"=>1
            ))
            ->addColumn('apply_info', 'text', array(
                "null"=>true,
                'comment' => "审批信息"
            ))
            ->addColumn('approver_info', 'text', array(
                "null"=>true,
                'comment' => "审批人信息"
            ))
            ->addColumn('approver', 'string', array(
                'limit' => 100,
                "null"=>true,
                'comment' => "审批人"
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
            ->addColumn('up_time', 'datetime', array(
                'comment' => "更新时间",
                "null"=>false
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
