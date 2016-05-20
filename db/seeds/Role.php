<?php

use Phinx\Seed\AbstractSeed;

class Role extends AbstractSeed
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
            "role_name"=>"超级管理员",
        );
        $data[] = array(
            "role_name"=>"普通管理员",
        );
        $data[] = array(
            "role_name"=>"财务",
        );
        $data[] = array(
            "role_name"=>"产品",
        );
        $posts = $this->table('org_role');
        $posts->insert($data)
            ->save();

    }
}
