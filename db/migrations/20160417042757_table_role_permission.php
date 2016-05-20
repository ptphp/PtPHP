<?php

use Phinx\Migration\AbstractMigration;

class TableRolePermission extends AbstractMigration
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
        $table = $this->table('role_perm',array('id' => false));
        $table
            ->addColumn('role_id', 'integer', array(
                'signed' => false
            ))
            ->addColumn('perm', 'text', array(
                'comment' => "permission",
                "null"=>true
            ))
            ->addIndex("role_id", array('unique' => true))
            ->create();
    }
}
