<?php

use Phinx\Migration\AbstractMigration;

class TableOaCheckin extends AbstractMigration
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
        $table = $this->table('oa_checkin',array('id' => false,"primary_key"=>"cki_id"));
        $table
            ->addColumn('cki_id', 'integer', array(
                'signed' => false,
                'identity'=>true
            ))
            ->addColumn('sno', 'string', array(
                'comment' => "编号",
                'null' => false,
                "limit"=>50
            ))

            ->addColumn('detail_name', 'string', array(
                "null"=>true,
                'limit'=>50,
                'comment' => "客户名称"
            ))
            ->addColumn('address', 'string', array(
                "null"=>true,
                'limit'=>120,
                'comment' => "签到地址"
            ))
            ->addColumn('proofs', 'text', array(
                "null"=>true,
                'comment' => "上传照片,|连接图片网址"
            ))
            ->addColumn('latitude', 'decimal', array(
                'comment' => "latitude",
                'null' => false,
                "default"=>0.0,
                "precision"=>15,
                "scale"=>12,
            ))
            ->addColumn('longitude', 'decimal', array(
                'comment' => "longitude",
                'null' => false,
                "default"=>0.0,
                "precision"=>15,
                "scale"=>12,
            ))
            ->addColumn('latx', 'decimal', array(
                'comment' => "latx",
                'null' => false,
                "default"=>0.0,
                "precision"=>15,
                "scale"=>12,
            ))
            ->addColumn('lngy', 'decimal', array(
                'comment' => "lngy",
                'null' => false,
                "default"=>0.0,
                "precision"=>15,
                "scale"=>12,
            ))

            ->addColumn('copyer', 'string', array(
                'limit' => 100,
                "null"=>true,
                'comment' => "抄送"
            ))
            ->addColumn('add_time', 'datetime', array(
                'comment' => "填写时间",
                "null"=>false
            ))
            ->addColumn('from_uid', 'integer', array(
                'signed' => false,
                'comment' => "签到人",
                "null"=>false
            ))
            ->create();
    }
}
