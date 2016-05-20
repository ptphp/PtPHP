<?php

use Phinx\Migration\AbstractMigration;

class MissionTask extends AbstractMigration
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
        $table = $this->table('mission_task');
        $table
            ->addColumn('award', 'decimal',array(
                "comment"=>"奖励绿电金额",
                "precision"=>10,
                "scale"=>1,
                "null"=>false,
                "default"=>0.0
            ))
            ->addColumn('mission_id', 'integer',array(
                "comment"=>"任务id",
                "limit"=>11,
                "null"=>false
            ))
            ->addColumn('title', 'string',array(
                "limit"=>120,
                "null"=>true,
                "comment"=>"子任务描述",
            ))
            ->create();
    }
}
