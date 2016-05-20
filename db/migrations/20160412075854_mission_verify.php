<?php

use Phinx\Migration\AbstractMigration;

class MissionVerify extends AbstractMigration
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
        $table = $this->table('user_mission_verify');
        $table
            ->addColumn('user_id', 'integer',array(
                "comment"=>"user id",
                "null"=>false,
                "limit"=>11
            ))
            ->addColumn('mission_id', 'integer',array(
                "comment"=>"任务ID",
                "null"=>false,
                "limit"=>11
            ))
            ->addColumn('task_key', 'integer',array(
                "comment"=>"任务序号",
                "null"=>false,
                "default"=>0,
                "limit"=>11
            ))
            ->addColumn('add_time', 'datetime',array(
                "comment"=>"提交审核时间",
                "null"=>true,
            ))
            ->addColumn('up_time', 'datetime',array(
                "comment"=>"更新时间",
                "null"=>true,
            ))
            ->addColumn('reason', 'string',array(
                "comment"=>"审核意见",
                "null"=>true,
                "limit"=>120
            ))
            ->addColumn('status', 'integer',array(
                "comment"=>"状态: 0 审核中 ; 1 通过 ; 2 拒绝 ",
                "null"=>false,
                "default"=>0
            ))
            ->addColumn('pics', 'text',array(
                "comment"=>"审核图片网址:|拼接",
                "null"=>false,
            ))
            ->addColumn('note', 'text',array(
                "comment"=>"备注",
                "null"=>true,
            ))
            ->create();

    }
}
