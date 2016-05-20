<?php

use Phinx\Migration\AbstractMigration;

class TableExpUserStore extends AbstractMigration
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
        //快递用户店铺
        $table = $this->table('exp_user_store',array("id"=>false,"primary_key"=>array("user_id","store_id")));
        $table
            ->addColumn('user_id', 'integer', array(
                'comment' => "user_id",
                "null"=>false,
                "signed"=>false
            ))
            ->addColumn('store_id', 'integer', array(
                'comment' => "store_id",
                "null"=>false,
                "signed"=>false
            ))
            ->create();
    }
}
