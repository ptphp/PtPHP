<?php

use Phinx\Migration\AbstractMigration;

class TableExpShip extends AbstractMigration
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
        //快递运单
        $table = $this->table('exp_shop',array('id' => false,"primary_key"=>"ship_id"));
        $table
            ->addColumn('ship_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('op_uid', 'integer', array(
                'signed' => false,
                "null"=>false,
                'comment' => "操作员uid",
            ))
            ->addColumn('ord_id', 'integer', array(
                'signed' => false,
                "null"=>true,
                'comment' => "订单ID",
            ))
            ->addColumn('dst_name', 'string', array(
                'comment' => "收件人姓名",
                "limit"=>50,
                'null' => false,
            ))
            ->addColumn('dst_phone', 'string', array(
                'comment' => "收件人手机或电话",
                "limit"=>50,
                'null' => false,
            ))
            ->addColumn('dst_prov', 'string', array(
                'comment' => "收件人省",
                "limit"=>10,
                'null' => false,
            ))
            ->addColumn('dst_city', 'string', array(
                'comment' => "收件人城市",
                "limit"=>10,
                'null' => false,
            ))
            ->addColumn('dst_dist', 'string', array(
                'comment' => "收件人区县",
                "limit"=>10,
                'null' => true,
            ))
            ->addColumn('dst_addr', 'string', array(
                'comment' => "收件人地址",
                "limit"=>200,
                'null' => false,
            ))
            ->addColumn('adr_name', 'string', array(
                'comment' => "寄件人姓名",
                "limit"=>50,
                'null' => false,
            ))
            ->addColumn('adr_phone', 'string', array(
                'comment' => "寄件人手机或电话",
                "limit"=>50,
                'null' => false,
            ))
            ->addColumn('adr_prov', 'string', array(
                'comment' => "寄件人省",
                "limit"=>10,
                'null' => false,
            ))
            ->addColumn('adr_city', 'string', array(
                'comment' => "寄件人市",
                "limit"=>10,
                'null' => false,
            ))
            ->addColumn('adr_dist', 'string', array(
                'comment' => "寄件人区",
                "limit"=>10,
                'null' => true,
            ))
            ->addColumn('adr_addr', 'string', array(
                'comment' => "寄件人地址",
                "limit"=>200,
                'null' => false,
            ))
            ->addColumn('store_id', 'integer', array(
                'comment' => "店铺id",
                'null' => true,
                "signed"=>false
            ))
            ->addColumn('com_id', 'integer', array(
                'comment' => "快递公司id",
                'null' => false,
                "signed"=>false
            ))
            ->addColumn('exp_type', 'boolean', array(
                'comment' => "类型:0 文件;1 包裹",
                'null' => true,
                'signed'=>false,
            ))
            ->addColumn('exp_num', 'string', array(
                'comment' => "快递单号",
                'limit'=>50,
                'null' => true,
            ))
            ->addColumn('shf_num', 'string', array(
                'comment' => "货架号",
                'limit'=>50,
                'null' => true,
            ))
            ->addColumn('dst_pay', 'boolean', array(
                'comment' => "到付",
                'null' => false,
                'signed'=>false,
                "default"=>0
            ))
            ->addColumn('price', 'decimal', array(
                'comment' => "价格",
                'null' => false,
                "default"=>0,
                "precision"=>7,
                "scale"=>2,
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
            ->addColumn('weight', 'decimal', array(
                'comment' => "称重",
                'null' => false,
                "default"=>0,
                "precision"=>7,
                "scale"=>2,
            ))
            ->addColumn('long', 'decimal', array(
                'comment' => "长",
                'null' => false,
                "default"=>0,
                "precision"=>7,
                "scale"=>2,
            ))
            ->addColumn('width', 'decimal', array(
                'comment' => "宽",
                'null' => false,
                "default"=>0,
                "precision"=>7,
                "scale"=>2,
            ))
            ->addColumn('height', 'decimal', array(
                'comment' => "高",
                'null' => false,
                "default"=>0,
                "precision"=>7,
                "scale"=>2,
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
