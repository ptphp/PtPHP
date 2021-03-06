<?php

use Phinx\Migration\AbstractMigration;

class TableOrgRole extends AbstractMigration
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
        $table = $this->table('org_role',array('id' => false,"primary_key"=>"role_id"));
        $table
            ->addColumn('role_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('role_name', 'string', array(
                'comment' => "role_name",
                "limit"=>50,
                'null' => false,
            ))
            ->create();
    }
}
