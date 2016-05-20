<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class TableCrmStore extends AbstractMigration
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
        $table = $this->table('crm_store',array('id' => false,"primary_key"=>"sto_id"));
        $table
            ->addColumn('sto_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('name', 'string', array(
                'comment' => "店名",
                'null' => true,
                "limit"=>50
            ))
            ->addColumn('addr', 'string', array(
                'comment' => "地址",
                'null' => true,
                "limit"=>120
            ))
            ->addColumn('charger_name', 'string', array(
                'comment' => "负责人姓名",
                'null' => true,
                "limit"=>10
            ))
            ->addColumn('charger_position', 'string', array(
                'comment' => "负责人职位",
                'null' => true,
                "limit"=>20
            ))
            ->addColumn('tel', 'string', array(
                'comment' => "电话",
                'null' => true,
                "limit"=>24
            ))
            ->addColumn('agent_id', 'integer', array(
                'signed' => false,
                'comment' => "关联代理商",
                "limit"=>MysqlAdapter::INT_SMALL,
                "null"=>true
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
            ->addColumn('add_time', 'datetime', array(
                'comment' => "填写日期",
                "null"=>false
            ))
            ->addColumn('op_uid', 'integer', array(
                'signed' => false,
                'comment' => "填写人",
                "null"=>false
            ))
            ->create();
    }
}
