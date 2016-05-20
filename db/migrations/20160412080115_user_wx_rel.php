<?php

use Phinx\Migration\AbstractMigration;

class UserWxRel extends AbstractMigration
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
        $table = $this->table('user_wx_rel');
        $table
            ->addColumn('user_id', 'integer',array(
                "comment"=>"user_id",
                "null"=>false,
                "limit"=>11
            ))
            ->addColumn('openid', 'string',array(
                "comment"=>"openid",
                "null"=>false,
                "limit"=>64
            ))
            ->addColumn('bind_time', 'datetime',array(
                "comment"=>"bind_time",
                "null"=>false,
            ))
            ->addIndex(array("user_id","openid"))
            ->create();
    }
}
