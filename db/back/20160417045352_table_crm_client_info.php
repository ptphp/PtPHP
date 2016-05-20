<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class TableCrmClientInfo extends AbstractMigration
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
        $table = $this->table('crm_client_info');
        $table
            ->addColumn('clt_id', 'integer', array(
                'signed' => false
            ))
            ->addColumn('height', 'decimal', array(
                'comment' => "身高",
                'null' => true,
                "precision"=>4,
                "scale"=>1,
            ))
            ->addColumn('weight', 'decimal', array(
                'comment' => "体重",
                'null' => true,
                "precision"=>4,
                "scale"=>1,
            ))
            ->addColumn('career', 'string', array(
                'comment' => "职业",
                'null' => true,
                "limit"=>50
            ))
            ->addColumn('year_earn', 'decimal', array(
                'comment' => "综合年收入",
                'null' => true,
                "precision"=>11,
                "scale"=>1,
            ))
            ->addColumn('education_level', 'char', array(
                'comment' => "文化程度 0 小学 1 初中 2 高中 3 本科 4 硕士 5 博士 6 博士后 7 其他",
                "limit"=>1,
                "null"=>true
            ))
            ->addColumn('car_type', 'string', array(
                'comment' => "座驾",
                'null' => true,
                "limit"=>50
            ))
            ->addColumn('marital_status', 'char', array(
                'comment' => "婚姻状况 0 未婚 1 已婚 2 离异",
                'signed' => false,
                "limit"=>1,
                "null"=>true
            ))
            ->addColumn('mate_status', 'string', array(
                'comment' => "配偶状况",
                'null' => true,
                "limit"=>50
            ))
            ->addColumn('fengshui', 'boolean', array(
                'comment' => "是否信风水 0 不信 1 信",
                'signed' => false,
                "null"=>true
            ))
            ->addColumn('dislike', 'string', array(
                'comment' => "特别反感的事",
                'null' => true,
                "limit"=>200
            ))
            ->addColumn('character', 'string', array(
                'comment' => "性格特点",
                'null' => true,
                "limit"=>200
            ))
            ->addColumn('lover', 'boolean', array(
                'comment' => "是否有情人 0 没有 1 有",
                'signed' => false,
                "null"=>true
            ))
            ->addColumn('mate_lover', 'boolean', array(
                'comment' => "配偶是否有情人 0 没有 1 有",
                'signed' => false,
                "null"=>true
            ))
            ->addColumn('capital_type', 'char', array(
                'comment' => "资金类型 0 自己有钱 1 家族有钱 2 配偶有钱 3 其他",
                "limit"=>1,
                "null"=>true
            ))
            ->addColumn('consume_like', 'char', array(
                'comment' => "消费喜好：0 奢侈品 1 健康美容 2 学习成长 3 投资理财",
                "limit"=>1,
                "null"=>true
            ))
            ->addColumn('consume_type', 'char', array(
                'comment' => "消费类型：0 冲动型 1 理性型 2 被动型 3 其他",
                "limit"=>1,
                "null"=>true
            ))
            ->addColumn('house_type', 'char', array(
                'comment' => "住宅级别：0 豪宅 1 别墅 2 公寓 3 一般",
                "limit"=>1,
                "null"=>true
            ))
            ->addColumn('years', 'char', array(
                'comment' => "客情情况：1:1年 ; 2 :2年; 3:5年以上",
                "limit"=>1,
                "null"=>true
            ))
            ->addColumn('club_y_c', 'string', array(
                'comment' => "会所年消费",
                'null' => true,
                "limit"=>50
            ))
            ->addColumn('max_c_p', 'string', array(
                'comment' => "单次最大的消费额及项目",
                'null' => true,
                "limit"=>249
            ))
            ->addColumn('out_do', 'string', array(
                'comment' => "在外面做过什么整形项目及价位",
                'null' => true,
                "limit"=>249
            ))
            ->addColumn('has_zhen', 'boolean', array(
                'comment' => "是否接受过整形或微整形 0 没有 1 有",
                'signed' => false,
                "null"=>true
            ))
            ->addColumn('change_party', 'string', array(
                'comment' => "顾客最想改善的部位",
                'null' => true,
                "limit"=>50
            ))
            ->addColumn('note', 'string', array(
                'comment' => "备注",
                'null' => true,
                "limit"=>249
            ))
            ->addColumn('add_time', 'datetime', array(
                'comment' => "填写日期",
                "null"=>false
            ))
            ->addColumn('op_uid', 'integer', array(
                'signed' => false,
                'comment' => "填写人",
            ))
            ->addIndex(array("clt_id"), array('unique' => true))
            ->create();
    }
}
