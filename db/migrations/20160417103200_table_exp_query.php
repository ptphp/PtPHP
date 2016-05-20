<?php

use Phinx\Migration\AbstractMigration;

class TableExpQuery extends AbstractMigration
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
        //快递查询
        $table = $this->table('exp_query');
        $table
            ->addColumn('user_id', 'integer', array(
                'signed' => false,
                "null"=>false,
            ))
            ->addColumn('com_id', 'integer', array(
                'signed' => false,
                "null"=>false,
                "limit"=>\Phinx\Db\Adapter\MysqlAdapter::INT_SMALL
            ))
            ->addColumn('result', 'text', array(
                'comment' => "查询结果",
                'null' => false,
            ))
            ->addColumn('add_time', 'datetime', array(
                'comment' => "添加时间",
                "null"=>false
            ))
            ->addColumn('up_time', 'datetime', array(
                'comment' => "更新时间",
                "null"=>true
            ))
            ->create();
    }
}
