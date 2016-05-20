<?php

use Phinx\Migration\AbstractMigration;

class TableSystemSetting extends AbstractMigration
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
        $table = $this->table('sys_setting',array('id' => false,"primary_key"=>"set_id"));
        $table
            ->addColumn('set_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('set_key', 'string', array(
                'comment' => "key",
                "limit"=>100,
                'null' => false,
            ))
            ->addColumn('set_title', 'string', array(
                'comment' => "title",
                "limit"=>100,
                'null' => false,
            ))
            ->addColumn('set_value', 'text', array(
                'comment' => "value",
                'null' => false,
            ))
            ->create();
    }
}
