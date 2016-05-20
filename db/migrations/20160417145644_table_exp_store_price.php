<?php

use Phinx\Migration\AbstractMigration;

class TableExpStorePrice extends AbstractMigration
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
        //快递价格
        $table = $this->table('exp_store_price');
        $table
            ->addColumn('com_id', 'integer', array(
                'comment' => "快递公司",
                'signed' => false,
                'null'=>false
            ))
            ->addColumn('store_id', 'integer', array(
                'comment' => "店铺",
                'signed' => false,
                'null'=>false
            ))
            ->addColumn('sz', 'decimal', array(
                'comment' => "首重:公斤",
                'null' => false,
                "default"=>1,
                "precision"=>3,
                "scale"=>1,
            ))
            ->addColumn('md', 'decimal', array(
                'comment' => "面单费",
                'null' => false,
                "default"=>0,
                "precision"=>5,
                "scale"=>1,
            ))
            ->addColumn('else', 'decimal', array(
                'comment' => "其他费用",
                'null' => false,
                "default"=>0,
                "precision"=>5,
                "scale"=>1,
            ))
            ->addColumn('jgsx', 'decimal', array(
                'comment' => "价格上限",
                'null' => false,
                "default"=>0,
                "precision"=>5,
                "scale"=>1,
            ))
            ->addColumn('pjtc', 'decimal', array(
                'comment' => "派件提成",
                'null' => false,
                "default"=>0,
                "precision"=>5,
                "scale"=>1,
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
