<?php

use Phinx\Migration\AbstractMigration;

class TableOaAssign extends AbstractMigration
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
        $table = $this->table('oa_assign',array('id' => false,"primary_key"=>"asn_id"));
        $table
            ->addColumn('asn_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('sno', 'string', array(
                'comment' => "编号",
                'null' => false,
                "limit"=>50
            ))
            ->addColumn('assign_info', 'text', array(
                "null"=>true,
                'comment' => "指令信息"
            ))
            ->addColumn('assigner', 'string', array(
                'limit' => 100,
                "null"=>true,
                'comment' => "抄送"
            ))
            ->addColumn('assigner_info', 'text', array(
                "null"=>true,
                'comment' => "指令完成信息"
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
