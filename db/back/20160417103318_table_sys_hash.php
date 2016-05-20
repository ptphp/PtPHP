<?php

use Phinx\Migration\AbstractMigration;

class TableSysHash extends AbstractMigration
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
        $table = $this->table('sys_hash',array("id"=>false,"primary_key"=>array("hash","key")));
        $table
            ->addColumn('hash', 'string', array(
                "null"=>false,
                "limit"=>"50"
            ))
            ->addColumn('key', 'string', array(
                "null"=>false,
                "limit"=>"50"
            ))
            ->addColumn('title', 'string', array(
                "null"=>false,
                "limit"=>50
            ))
            ->addColumn('value', 'text', array(
                "null"=>false,
            ))
            ->addColumn('ord', 'integer', array(
                "null"=>false,
                "limit"=>\Phinx\Db\Adapter\MysqlAdapter::INT_SMALL
            ))
            ->create();
        $rows = array();
        $rows[] = array(
            "hash"=>"exp",
            "key"=>"sms_template",
            "title"=>"首次短信提醒",
            "value"=>'您有{$company}快递到{$campus},早11点至晚5:30点前到{$address}领取,编号{$xd_num}电话{$mobile}',
            "ord"=>1,
        );
        $rows[] = array(
            "hash"=>"exp",
            "key"=>"sms_re_template",
            "title"=>"滞留件提醒",
            "value"=>'[滞留件提醒] 亲！您有{$company}到{$campus},早11点至晚5:30点前到{$address}领取,编号{$xd_num},电话{$mobile}',
            "ord"=>2,
        );

        $this->insert('sys_hash', $rows);
    }
}
