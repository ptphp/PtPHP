<?php

use Phinx\Migration\AbstractMigration;

class TableOrgPosition extends AbstractMigration
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
        $table = $this->table('org_position',array('id' => false,"primary_key"=>"pot_id"));
        $table
            ->addColumn('pot_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('pot_name', 'string', array(
                'comment' => "position name",
                "limit"=>50,
                'null' => false,
            ))
            ->addColumn('dep_id', 'integer', array(
                'comment' => "department id",
                'signed' => false,
                "null"=>true,
            ))
            ->create();
    }
}
