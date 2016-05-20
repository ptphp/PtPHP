<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;
class TableUser extends AbstractMigration
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
        $table = $this->table('user',array('id' => false,"primary_key"=>"user_id"));
        $table
            ->addColumn('user_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('password', 'string',array(
                "comment"=>"password",
                "limit"=>32,
                "null"=>true,
            ))
            ->addColumn('mobile', 'string',array(
                "comment"=>"mobile",
                "limit"=>11,
                "null"=>true,
            ))
            ->addColumn('email', 'string',array(
                "comment"=>"mobile",
                "limit"=>120,
                "null"=>true,
            ))
            ->addColumn('status', 'boolean',array(
                "comment"=>"status",
                "null"=>false,
                "default"=>"1",
                "signed"=>false
            ))
            ->addColumn('reg_ip', 'string',array(
                "comment"=>"reg_ip",
                "null"=>true,
                "limit"=>15,
            ))
            ->addColumn('add_time', 'datetime',array(
                "comment"=>"add_time",
                "null"=>false,
            ))
            ->addIndex(array("mobile","status"))
            ->addIndex(array("email","status"))
            ->create();
    }
    public function down(){

    }
}
