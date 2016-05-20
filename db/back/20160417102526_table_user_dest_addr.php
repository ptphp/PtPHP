<?php

use Phinx\Migration\AbstractMigration;

class TableUserDestAddr extends AbstractMigration
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
        //快递收件地址
        $table = $this->table('user_dest_addr',array('id' => false,"primary_key"=>"dst_id"));
        $table
            ->addColumn('dst_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('user_id', 'integer', array(
                'signed' => false,
                "null"=>false,
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
            ->addColumn('add_time', 'datetime', array(
                'comment' => "填写时间",
                "null"=>false
            ))
            ->create();

    }
}
