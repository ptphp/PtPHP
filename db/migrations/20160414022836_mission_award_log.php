<?php

use Phinx\Migration\AbstractMigration;

class MissionAwardLog extends AbstractMigration
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
        $table = $this->table('mission_award_log');
        $table
            ->addColumn('user_id', 'integer',array(
                "comment"=>"user_id",
                "null"=>false,
                "limit"=>11,
            ))
            ->addColumn('mission_id', 'integer',array(
                "comment"=>"mission_id",
                "null"=>false,
                "limit"=>11,
            ))
            ->addColumn('task_key', 'integer',array(
                "comment"=>"task_key",
                "null"=>false,
                "limit"=>11,
            ))
            ->addColumn('award', 'decimal',array(
                "comment"=>"奖励绿电金额",
                "precision"=>10,
                "scale"=>1,
                "null"=>false,
                "default"=>0.0
            ))
            ->addColumn('add_time', 'datetime',array(
                "comment"=>"add_time",
                "null"=>false,
            ))
            ->addColumn('status', 'integer',array(
                "comment"=>"返绿电: 0 已提交;1 成功;2 失败",
                "null"=>false,
            ))
            ->create();
    }
}
