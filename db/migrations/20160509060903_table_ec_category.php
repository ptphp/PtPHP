<?php

use Phinx\Migration\AbstractMigration;

class TableEcCategory extends AbstractMigration
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
        $table = $this->table('ec_category',array('id' => false,"primary_key"=>"cat_id"));
        $table
            ->addColumn('cat_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('cat_name', 'string', array(
                'comment' => "cat name",
                "limit"=>50,
                'null' => false,
            ))
            ->addColumn('cat_thumb', 'string', array(
                'comment' => "cat name",
                "limit"=>254,
                'null' => true,
            ))
            ->addColumn('cat_pid', 'integer', array(
                'comment' => "cat parent id",
                'signed' => false,
                "default"=>0,
                "null"=>false,
            ))
            ->create();
    }
}
