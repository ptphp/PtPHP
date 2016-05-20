<?php

use Phinx\Migration\AbstractMigration;

class TableCrmPorder extends AbstractMigration
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
        $table = $this->table('crm_porder',array('id' => false,"primary_key"=>"pord_id"));
        $table
            ->addColumn('pord_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('clt_id', 'integer', array(
                'signed' => false,
                'null'=>false
            ))
            ->addColumn('orderno', 'string', array(
                'comment' => "订单号",
                'null' => false,
                "limit"=>50
            ))
            ->addColumn('designer_uid', 'integer', array(
                'signed' => false,
                'comment' => "设计师",
                "null"=>true
            ))
            ->addColumn('nutri', 'string', array(
                'limit' => 50,
                "null"=>true,
                'comment' => "营养状况"
            ))
            ->addColumn('blood', 'string', array(
                'limit' => 20,
                "null"=>true,
                'comment' => "血型"
            ))
            ->addColumn('parts', 'string', array(
                'limit' => 50,
                "null"=>true,
                'comment' => "已做过部位"
            ))
            ->addColumn('material', 'string', array(
                'limit' => 254,
                "null"=>true,
                'comment' => "材料"
            ))
            ->addColumn('item', 'string', array(
                'limit' => 254,
                "null"=>true,
                'comment' => "手术项目"
            ))
            ->addColumn('years', 'string', array(
                'limit' => 20,
                "null"=>true,
                'comment' => "年限"
            ))
            ->addColumn('allergies', 'string', array(
                'limit' => 100,
                "null"=>true,
                'comment' => "过敏史"
            ))
            ->addColumn('blood_pressure', 'string', array(
                'limit' => 50,
                "null"=>true,
                'comment' => "血压情况"
            ))
            ->addColumn('medical_history', 'string', array(
                'limit' => 254,
                "null"=>true,
                'comment' => "病史"
            ))
            ->addColumn('medicine', 'string', array(
                'limit' => 254,
                "null"=>true,
                'comment' => "正在服用药物"
            ))
            ->addColumn('solution1', 'string', array(
                'limit' => 254,
                "null"=>true,
                'comment' => "专家设计方案一"
            ))
            ->addColumn('solution2', 'string', array(
                'limit' => 254,
                "null"=>true,
                'comment' => "专家设计方案二"
            ))
            ->addColumn('request', 'string', array(
                'limit' => 254,
                "null"=>true,
                'comment' => "顾客对手术要求"
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
