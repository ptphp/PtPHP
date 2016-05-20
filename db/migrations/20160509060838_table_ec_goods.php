<?php

use Phinx\Migration\AbstractMigration;

class TableEcGoods extends AbstractMigration
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
        $table = $this->table('ec_goods',array('id' => false,"primary_key"=>"god_id"));
        $table
            ->addColumn('god_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('god_name', 'string', array(
                'comment' => "goods name",
                "limit"=>50,
                'null' => false,
            ))
            ->addColumn('god_thumb', 'text', array(
                'comment' => "goods thumb",
                "limit"=>254,
                'null' => true,
            ))
            ->addColumn('cat_id', 'integer', array(
                'comment' => "cat id",
                'signed' => false,
                "default"=>null,
                "null"=>false,
            ))
            ->addColumn('price', 'decimal', array(
                'comment' => "单价",
                'null' => false,
                "default"=>0.0,
                "precision"=>7,
                "scale"=>1,
            ))
            ->addColumn('ori_price', 'decimal', array(
                'comment' => "原价",
                'null' => false,
                "default"=>0.0,
                "precision"=>7,
                "scale"=>1,
            ))
            ->addColumn('content', 'text',array(
                "comment"=>"详情描述",
                "null"=>true,
            ))
            ->addColumn('status', 'integer',array(
                "comment"=>"状态 1 上架; 2 下架",
                "limit"=>11,
                "null"=>true
            ))
            ->addColumn('is_rec', 'integer',array(
                "comment"=>"推荐 0 否; 1 是",
                "limit"=>11,
                "null"=>false,
                "default"=>0,
            ))
            ->addColumn('ord', 'integer',array(
                "comment"=>"排序 100起,100,99,98 降序排列 ",
                "limit"=>11,
                "null"=>false,
                "default"=>0,
            ))
            ->addColumn('stock', 'integer',array(
                "comment"=>"库存",
                "null"=>false,
                "default"=>10000,
                "limit"=>11
            ))
            ->addColumn('buy_nums', 'integer',array(
                "comment"=>"购买人数",
                "null"=>false,
                "default"=>0,
                "limit"=>11
            ))
            ->addColumn('pay_nums', 'integer',array(
                "comment"=>"支付人数",
                "null"=>false,
                "default"=>0,
                "limit"=>11
            ))
            ->addColumn('add_time', 'datetime', array(
                'comment' => "新加时间",
                "null"=>false
            ))
            ->addColumn('up_time', 'datetime', array(
                'comment' => "更新时间",
                "null"=>true
            ))
            ->create();
    }
}
