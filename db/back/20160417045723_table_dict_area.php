<?php

use Phinx\Migration\AbstractMigration;

class TableDictArea extends AbstractMigration
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
        $table = $this->table('dict_area',array('id' => false,"primary_key"=>"area_id"));
        $table
            ->addColumn('area_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('area_name', 'string', array(
                'comment' => "area_name",
                "limit"=>50,
                'null' => false,
            ))
            ->create();
    }
}
