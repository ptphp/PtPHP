<?php

use Phinx\Migration\AbstractMigration;

class TableSystemLog extends AbstractMigration
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
        $table = $this->table('sys_log',array('id' => false,"primary_key"=>"log_id"));
        $table
            ->addColumn('log_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('user_id', 'integer', array(
                'signed' => false,
            ))
            ->addColumn('content', 'text', array(
                'comment' => "",
                'null' => true,
            ))
            ->addColumn('method', 'string', array(
                'comment' => "",
                'limit'=>100,
                'null' => true,
            ))
            ->addColumn('ip', 'string', array(
                'comment' => "",
                'limit'=>15,
                'null' => true,
            ))
            ->addColumn('add_time', 'datetime', array(
                'comment' => "",
                'null' => true,
            ))
            ->create();
    }
}
