<?php

use Phinx\Migration\AbstractMigration;

class AddMission extends AbstractMigration
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
        $table = $this->table('mission');
        $table
            ->addColumn('title', 'string',array(
                "comment"=>"任务名称",
                "limit"=>100,
                "null"=>false
            ))
            ->addColumn('desc', 'string',array(
                "comment"=>"任务描述",
                "limit"=>254,
                "null"=>true
            ))
            ->addColumn('add_time', 'datetime',array(
                "comment"=>"添加时间",
            ))
            ->addColumn('com_name', 'string',array(
                "comment"=>"商家名称",
                "limit"=>50,
                "null"=>true
            ))
            ->addColumn('tag', 'integer',array(
                "comment"=>"任务标签: 1 最热;2 新手任务;3进阶任务",
                "limit"=>11,
                "null"=>false
            ))
            ->addColumn('type', 'integer',array(
                "comment"=>"任务类型: 1 签到;2 关注;3 注册;4 分享;5 下单;6 进阶",
                "limit"=>11,
                "null"=>false
            ))
            ->addColumn('thumb', 'string',array(
                "comment"=>"缩略图",
                "limit"=>254,
                "null"=>false
            ))
            ->addColumn('btn_name', 'string',array(
                "comment"=>"任务按钮名称",
                "limit"=>30,
                "null"=>true
            ))
            ->addColumn('sno', 'string',array(
                "comment"=>"序号",
                "limit"=>30,
                "null"=>true
            ))
            ->addColumn('platform', 'string',array(
                "comment"=>"商家平台类型: wechat 微信;app APP",
                "limit"=>10,
                "null"=>true
            ))
            ->addColumn('platform_name', 'string',array(
                "comment"=>"详情页平台名",
                "limit"=>50,
                "null"=>true
            ))
            ->addColumn('wechat_account', 'string',array(
                "comment"=>"微信号",
                "limit"=>80,
                "null"=>true
            ))
            ->addColumn('remain_times', 'integer',array(
                "comment"=>"任务剩余次数",
                "limit"=>11,
                "null"=>true,
                "default"=>null
            ))
            ->addColumn('award', 'decimal',array(
                "comment"=>"奖励绿电金额",
                "precision"=>10,
                "scale"=>1,
                "null"=>false,
                "default"=>0.0
            ))
            ->addColumn('start_time', 'datetime',array(
                "comment"=>"有效开始时间",
                "null"=>true,
            ))
            ->addColumn('end_time', 'datetime',array(
                "comment"=>"有效结束时间",
                "null"=>true,
            ))
            ->addColumn('tips', 'text',array(
                "comment"=>"任务说明",
                "null"=>true,
            ))
            ->addColumn('example', 'text',array(
                "comment"=>"示例文字",
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
            ->addColumn('note', 'text',array(
                "comment"=>"备注",
                "null"=>true,
            ))
            ->addColumn('ord', 'integer',array(
                "comment"=>"排序 100起,100,99,98 降序排列 ",
                "limit"=>11,
                "null"=>false,
                "default"=>0,
            ))
            ->addColumn('join_nums', 'integer',array(
                "comment"=>"参与人数",
                "null"=>false,
                "default"=>0,
                "limit"=>11
            ))
            ->addColumn('finish_nums', 'integer',array(
                "comment"=>"完成人数",
                "null"=>false,
                "default"=>0,
                "limit"=>11
            ))
            ->create();
    }
}
