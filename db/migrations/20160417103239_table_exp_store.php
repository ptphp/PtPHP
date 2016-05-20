<?php

use Phinx\Migration\AbstractMigration;

class TableExpStore extends AbstractMigration
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
        //快递店铺
        $table = $this->table('exp_store');
        $table
            ->addColumn('store_name', 'string', array(
                'limit' => 50,
                "null"=>false,
            ))
            ->addColumn('store_city', 'string', array(
                'comment' => "店铺城市",
                'limit' => 30,
                "null"=>false,
            ))
            ->addColumn('store_addr', 'string', array(
                'comment' => "店铺地址",
                'limit' => 120,
                "null"=>false,
            ))
            ->addColumn('store_phone', 'string', array(
                'comment' => "店铺电话",
                'limit' => 30,
                "null"=>false,
            ))
            ->addColumn('add_time', 'datetime', array(
                'comment' => "添加时间",
                "null"=>false
            ))
            ->addColumn('op_uid', 'integer', array(
                'comment' => "填加人",
                "null"=>false,
                "signed"=>false
            ))
            ->create();

    }
}
