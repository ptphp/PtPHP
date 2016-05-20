<?php

use Phinx\Migration\AbstractMigration;

class TableOrgDepartment extends AbstractMigration
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
        $table = $this->table('org_department',array('id' => false,"primary_key"=>"dep_id"));
        $table
            ->addColumn('dep_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('dep_name', 'string', array(
                'comment' => "department name",
                "limit"=>50,
                'null' => false,
            ))
            ->addColumn('dep_pid', 'integer', array(
                'comment' => "department parent id",
                'signed' => false,
                "default"=>0,
                "null"=>false,
            ))
            ->create();
    }
}
