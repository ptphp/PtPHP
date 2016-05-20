<?php

use Phinx\Migration\AbstractMigration;

class TableEcOrderItem extends AbstractMigration
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
        $table = $this->table('ec_order_item',array('id' => false));
        $table
            ->addColumn('ord_id', 'integer', array(
                'signed' => false,
            ))
            ->addColumn('god_id', 'integer', array(
                'signed' => false,
                'null'=>true
            ))
            ->addColumn('price_unit', 'decimal', array(
                'comment' => "单价",
                'null' => false,
                "default"=>0.0,
                "precision"=>5,
                "scale"=>1,
            ))
            ->addColumn('nums', 'integer', array(
                'comment' => "数量",
                'null' => false,
                "default"=>1,
            ))
            ->addIndex(array("ord_id"),array('unique' => true))
            ->create();
    }
}
