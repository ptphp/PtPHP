<?php

use Phinx\Migration\AbstractMigration;

class TableOrgStaff extends AbstractMigration
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
        $table = $this->table('org_staff',array('id' => false,"primary_key"=>"stf_id"));
        $table
            ->addColumn('stf_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('stf_name', 'string', array(
                'comment' => "staff name",
                'limit'=>50,
                'null' => false,
            ))
            ->addColumn('dep_id', 'integer', array(
                'comment' => "department id",
                'signed' => false,
                "null"=>true,
            ))
            ->addColumn('pot_id', 'integer', array(
                'comment' => "position id",
                'signed' => false,
                "null"=>true,
            ))
            ->addColumn('role_id', 'integer', array(
                'comment' => "role id",
                'signed' => false,
                "null"=>true,
            ))
            ->addColumn('sex', 'boolean', array(
                'comment' => "性别: 1 男 0 女",
                'null' => true,
                "default"=>1
            ))
            ->addColumn('mobile', 'string', array(
                'comment' => "mobile",
                "null"=>true,
                "limit"=>11
            ))
            ->addColumn('py', 'string', array(
                'comment' => "pin yin",
                "null"=>true,
                "limit"=>200
            ))
            ->addColumn('add_time', 'datetime', array(
                'comment' => "add_time",
                "null"=>false,
            ))
            ->create();
    }
}
