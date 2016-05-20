<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class TableCrmClient extends AbstractMigration
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
        $table = $this->table('crm_client',array('id' => false,"primary_key"=>"clt_id"));
        $table
            ->addColumn('clt_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('name', 'string', array(
                'comment' => "客户姓名",
                'null' => false,
                "limit"=>10
            ))
            ->addColumn('sex', 'boolean', array(
                'comment' => "性别: 1 男 0 女",
                "signed"=>false,
                'null' => false
            ))
            ->addColumn('age', 'integer', array(
                'comment' => "age",
                'signed' => false,
                'null' => true,
                "limit"=>MysqlAdapter::INT_TINY
            ))
            ->addColumn('tel', 'string', array(
                'comment' => "电话",
                'null' => true,
                "limit"=>24
            ))
            ->addColumn('addr', 'string', array(
                'comment' => "地址",
                'null' => true,
                "limit"=>120
            ))
            ->addColumn('province_id', 'integer', array(
                'signed' => false,
                'comment' => "所属省份",
                "limit"=>MysqlAdapter::INT_SMALL,
                "null"=>true
            ))
            ->addColumn('area_id', 'integer', array(
                'signed' => false,
                'comment' => "所属区域",
                "limit"=>MysqlAdapter::INT_SMALL,
                "null"=>true
            ))
            ->addColumn('store_id', 'integer', array(
                'signed' => false,
                'comment' => "所属店铺",
                "limit"=>MysqlAdapter::INT_SMALL,
                "null"=>true
            ))
            ->addColumn('zxs_uid', 'integer', array(
                'signed' => false,
                'comment' => "咨询师",
                "null"=>true
            ))
            ->addColumn('zxz_uid', 'integer', array(
                'signed' => false,
                'comment' => "咨询总监",
                "null"=>true
            ))
            ->addColumn('kef_uid', 'integer', array(
                'signed' => false,
                'comment' => "客服人员",
                "null"=>true
            ))
            ->addColumn('yl_uid', 'integer', array(
                'signed' => false,
                'comment' => "医疗指派",
                "null"=>true
            ))
            ->addColumn('sj_uid', 'integer', array(
                'signed' => false,
                'comment' => "设计",
                "null"=>true
            ))
            ->addColumn('ys_uid', 'integer', array(
                'signed' => false,
                'comment' => "医生",
                "null"=>true
            ))
            ->addColumn('copyer', 'string', array(
                'limit' => 100,
                "null"=>true,
                'comment' => "抄送"
            ))
            ->addColumn('add_time', 'datetime', array(
                'comment' => "填写日期",
                "null"=>false
            ))
            ->addColumn('op_uid', 'integer', array(
                'signed' => false,
                'comment' => "填写人",
            ))
            ->create();
    }
}
