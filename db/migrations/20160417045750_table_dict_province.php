<?php

use Phinx\Migration\AbstractMigration;

class TableDictProvince extends AbstractMigration
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
        $table = $this->table('dict_province',array('id' => false,"primary_key"=>"prov_id"));
        $table
            ->addColumn('prov_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('prov_name', 'string', array(
                'comment' => "prov_name",
                "limit"=>50,
                'null' => false,
            ))
            ->create();
    }
}
