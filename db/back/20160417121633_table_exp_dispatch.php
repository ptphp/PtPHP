<?php

use Phinx\Migration\AbstractMigration;

class TableExpDispatch extends AbstractMigration
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
        //代收件
        $table = $this->table('exp_dispatch',array('id' => false,"primary_key"=>"disp_id"));
        $table
            ->addColumn('disp_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('op_uid', 'integer', array(
                'signed' => false,
                "null"=>false,
                'comment' => "操作员uid",
            ))
            ->addColumn('shf_num', 'string', array(
                'comment' => "货架号",
                'limit'=>50,
                'null' => true,
            ))
            ->addColumn('dst_phone', 'string', array(
                'comment' => "收件人手机",
                "limit"=>11,
                'null' => false,
            ))
            ->addColumn('profit', 'decimal', array(
                'comment' => "利润",
                'null' => false,
                "default"=>0,
                "precision"=>7,
                "scale"=>2,
            ))
            ->addColumn('cost', 'decimal', array(
                'comment' => "成本",
                'null' => false,
                "default"=>0,
                "precision"=>7,
                "scale"=>2,
            ))
            ->addColumn('dst_pay', 'boolean', array(
                'comment' => "到付",
                'null' => false,
                'signed'=>false,
                "default"=>0
            ))
            ->addColumn('has_pay', 'boolean', array(
                'comment' => "已付",
                'null' => false,
                'signed'=>false,
                "default"=>0
            ))
            ->addColumn('has_taken', 'boolean', array(
                'comment' => "已取",
                'null' => false,
                'signed'=>false,
                "default"=>0
            ))
            ->addColumn('take_time', 'datetime', array(
                'comment' => "提取时间",
                "null"=>false
            ))
            ->addColumn('notice_type', 'char', array(
                'comment' => "通知类型:1 短信 ;2 wechat",
                'limit'=>1,
                'null' => false,
                "default"=>1
            ))
            ->addColumn('notice_time', 'datetime', array(
                'comment' => "通知时间",
                'null' => true,
            ))
            ->addColumn('add_time', 'datetime', array(
                'comment' => "填写时间",
                "null"=>false
            ))
            ->addColumn('note', 'string', array(
                'comment' => "note",
                'limit'=>200,
                'null' => true,
            ))
            ->create();
    }
}
