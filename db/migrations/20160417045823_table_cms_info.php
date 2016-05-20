<?php

use Phinx\Migration\AbstractMigration;

class TableCmsInfo extends AbstractMigration
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
        $table = $this->table('cms_info',array('id' => false,"primary_key"=>"info_id"));
        $table
            ->addColumn('info_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('info_pid', 'integer', array(
                'signed' => false,
                "null"=>false,
                "default"=>0
            ))
            ->addColumn('type', 'char', array(
                'comment' => "1 图片 ;2 图文 ; 3 链接",
                "limit"=>1,
                'null' => true,
            ))
            ->addColumn('img_url', 'text', array(
                'comment' => "图片地址",
                'null' => true,
            ))
            ->addColumn('text', 'text', array(
                'comment' => "文本内容",
                'null' => true,
            ))
            ->addColumn('url', 'string', array(
                'comment' => "链接",
                'null' => true,
            ))
            ->addColumn('add_time', 'datetime', array(
                'comment' => "填写日期",
                "null"=>false
            ))
            ->create();
    }
}
