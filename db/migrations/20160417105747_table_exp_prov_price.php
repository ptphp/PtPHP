<?php

use Phinx\Migration\AbstractMigration;

class TableExpProvPrice extends AbstractMigration
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
        $table = $this->table('exp_prov_price');
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
            ->addColumn('prov', 'string', array(
                'comment' => "省份",
                'limit' => 30,
                'null'=>false
            ))
            ->addColumn('sz_cb', 'decimal', array(
                'comment' => "首重成本",
                'null' => false,
                "default"=>1,
                "precision"=>5,
                "scale"=>1,
            ))
            ->addColumn('sz_jg', 'decimal', array(
                'comment' => "首重价格",
                'null' => false,
                "default"=>1,
                "precision"=>5,
                "scale"=>1,
            ))
            ->addColumn('xz_cb', 'decimal', array(
                'comment' => "续重成本",
                'null' => false,
                "default"=>1,
                "precision"=>5,
                "scale"=>1,
            ))
            ->addColumn('xz_jg', 'decimal', array(
                'comment' => "续重价格",
                'null' => false,
                "default"=>1,
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
