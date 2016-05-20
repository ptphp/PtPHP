<?php

use Phinx\Seed\AbstractSeed;

class Department extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = array();
        $data[] = array(
            "dep_name"=>"部门列表",
            "dep_pid"=>"0",
        );
        $data[] = array(
            "dep_name"=>"总裁办",
            "dep_pid"=>"1",
        );
        $data[] = array(
            "dep_name"=>"财务部",
            "dep_pid"=>"1",
        );
        $data[] = array(
            "dep_name"=>"技术中心",
            "dep_pid"=>"1",
        );
        $data[] = array(
            "dep_name"=>"开发部",
            "dep_pid"=>"3",
        );
        $data[] = array(
            "dep_name"=>"设计部",
            "dep_pid"=>"3",
        );

        $posts = $this->table('org_department');
        $posts->insert($data)
            ->save();

        $data = array();
        $data[] = array(
            "pot_name"=>"办公室主任",
            "dep_id"=>"2",
        );
        $data[] = array(
            "pot_name"=>"前台",
            "dep_id"=>"2",
        );

        $data[] = array(
            "pot_name"=>"财务经理",
            "dep_id"=>"3",
        );
        $data[] = array(
            "pot_name"=>"出纳",
            "dep_id"=>"3",
        );
        $data[] = array(
            "pot_name"=>"会计",
            "dep_id"=>"3",
        );

        $posts = $this->table('org_position');
        $posts->insert($data)
            ->save();
    }
}
