<?php

use Phinx\Migration\AbstractMigration;

class TableBill extends AbstractMigration
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
        $table = $this->table('bill',array('id' => false,"primary_key"=>"bil_id"));
        $table
            ->addColumn('bil_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('bil_type', 'integer', array(
                'signed' => false,
                'comment' => "1 in; 2 out;",
                'limit' => 10,
            ))
            ->addColumn('bil_amount', 'decimal', array(
                'comment' => "金额",
                'null' => false,
                "default"=>0.0,
                "precision"=>12,
                "scale"=>1,
            ))
            ->addColumn('bil_kind', 'string', array(
                'signed' => false,
                'comment' => "帐单",
                'limit' => 10,
            ))
            ->addColumn('bil_note', 'string', array(
                'signed' => false,
                'comment' => "备注",
                'limit' => 120,
            ))
            ->addColumn('add_time', 'datetime', array(
                'comment' => "交易时间",
                "null"=>true
            ))
            ->create();
    }
}
