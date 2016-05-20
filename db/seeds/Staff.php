<?php

use Phinx\Seed\AbstractSeed;

class Staff extends AbstractSeed
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
            "stf_name"=>"李四",
            "dep_id"=>3,
            "pot_id"=>1,
            "role_id"=>3,
            "sex"=>1,
            "mobile"=>"13555555555",
            "py"=>"lisi",
        );
        $data[] = array(
            "stf_name"=>"王五",
            "dep_id"=>3,
            "pot_id"=>1,
            "role_id"=>3,
            "sex"=>1,
            "mobile"=>"13555555555",
            "py"=>"wangwu",
        );
        $data[] = array(
            "stf_name"=>"李六",
            "dep_id"=>3,
            "pot_id"=>1,
            "role_id"=>3,
            "sex"=>1,
            "mobile"=>"13555555555",
            "py"=>"liliu",
        );
        $posts = $this->table('org_staff');
        $posts->insert($data)
            ->save();


    }
}
