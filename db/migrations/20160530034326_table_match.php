<?php

use Phinx\Migration\AbstractMigration;

class TableMatch extends AbstractMigration
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
        $table = $this->table('match',array('id' => false,"primary_key"=>"mac_id"));
        $table
            ->addColumn('mac_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('to_uid', 'integer', array(
                'comment' => "收信人UID",
                'signed' => false,
                'null' => false,
            ))
            ->addColumn('from_uid', 'integer', array(
                'comment' => "发信人UID",
                'signed' => false,
                'null' => false,
            ))
            ->addColumn('rel_id', 'integer', array(
                'comment' => "关联ID",
                'signed' => false,
                'null' => false,
            ))
            ->addColumn('type', 'enum', array(
                "null"=>false,
                'values' => array("出差","报销","申购","请假","借款","返款","派车","特批","周报","日报","签到","审批")
            ))
            ->addColumn('msg_body', 'text', array(
                'comment' => "消息摘要",
                'null' => true,
            ))
            ->addColumn('is_read', 'boolean', array(
                'null' => false,
                'default'=>0,
            ))
            ->addColumn('read_time', 'datetime', array(
                'comment' => "阅读时间",
                "null"=>false
            ))
            ->addColumn('add_time', 'datetime', array(
                'comment' => "添加时间",
                "null"=>false
            ))
            ->addColumn('wx_send', 'boolean', array(
                'comment' => "是否微信模板消息通知",
                "null"=>false,
                "default"=>0,
            ))
            ->addColumn('wx_send_time', 'datetime', array(
                'comment' => "微信模板消息发送时间",
                "null"=>true
            ))
            ->addColumn('sms_send', 'boolean', array(
                'comment' => "是否短信通知",
                "null"=>false,
                "default"=>0,
            ))
            ->addColumn('sms_send_time', 'datetime', array(
                'comment' => "短信发送时间",
                "null"=>true
            ))
            ->create();
    }
}
