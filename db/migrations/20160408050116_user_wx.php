<?php

use Phinx\Migration\AbstractMigration;

class UserWx extends AbstractMigration
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
        $table = $this->table('user_wx');
        $table
            ->addColumn('openid', 'string',array(
                "comment"=>"openid",
                "null"=>false,
                "limit"=>64
            ))
            ->addColumn('unionid', 'string',array(
                "comment"=>"unionid",
                "null"=>true,
                "limit"=>64
            ))
            ->addColumn('avatar', 'string',array(
                "comment"=>"avatar",
                "null"=>true,
                "limit"=>254
            ))
            ->addColumn('nickname', 'string',array(
                "comment"=>"nickname",
                "null"=>true,
                "limit"=>100
            ))
            ->addColumn('info', 'text',array(
                "comment"=>"info",
                "null"=>true,
            ))
            ->addColumn('add_time', 'datetime',array(
                "comment"=>"add_time",
                "null"=>false,
            ))
            ->addIndex(array("openid"))
            ->addIndex(array("unionid"))
            ->create();
    }
}
