<?php

use Phinx\Migration\AbstractMigration;

class UserMission extends AbstractMigration
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
        $table = $this->table('user_mission');
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
                "comment"=>"当前任务序号",
                "null"=>false,
                "default"=>0,
                "limit"=>11
            ))
            ->addColumn('verify_id', 'integer',array(
                "comment"=>"当前审批ID",
                "null"=>true,
                "limit"=>11
            ))
            ->addColumn('begin_time', 'datetime',array(
                "comment"=>"开始时间",
                "null"=>false,
            ))
            ->addColumn('finish_time', 'datetime',array(
                "comment"=>"完成时间",
                "null"=>true,
            ))
            ->create();
    }
}
