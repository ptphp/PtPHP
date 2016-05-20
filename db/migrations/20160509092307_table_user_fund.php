<?php

use Phinx\Migration\AbstractMigration;

class TableUserFund extends AbstractMigration
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
        $table = $this->table('user_fund',array('id' => false));
        $table
            ->addColumn('user_id', 'integer', array(
                'signed' => false,
            ))
            ->addColumn('balance', 'decimal', array(
                'comment' => "余额",
                'null' => false,
                "default"=>0.0,
                "precision"=>12,
                "scale"=>1,
            ))
            ->addIndex(array("user_id"),array('unique' => true))
            ->create();
    }
}
