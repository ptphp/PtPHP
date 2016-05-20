<?php

use Phinx\Migration\AbstractMigration;

class TableUserAddr extends AbstractMigration
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
        //寄件人地址
        $table = $this->table('user_addr',array('id' => false,"primary_key"=>"adr_id"));
        $table
            ->addColumn('adr_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('user_id', 'integer', array(
                'signed' => false,
                "null"=>false,
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
                'comment' => "寄件人区县",
                "limit"=>10,
                'null' => true,
            ))
            ->addColumn('adr_addr', 'string', array(
                'comment' => "寄件人地址",
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
