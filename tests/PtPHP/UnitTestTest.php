<?php
use PtPHP\UnitTest as UnitTest;
class UnitTestTest extends UnitTest
{

    protected function setUp()
    {
    }

    public function testAddTestSuite()
    {
        $url = "http://www.sohu.com";
        $res = $this->http_request("get",$url,array(
            "data"=>array("username"=>"admin","password"=>111111),
            "setting"=>array(
                "debug"=>0,
                "post_json"=>0,
                "print_response"=>0,
                "print_response_header"=>0,
                "print_response_cookie"=>0,
                "print_response_info"=>0,
                "cookie_file"=>COOKIE_FILE,
                "local_proxy"=>0,
            )
        ));
        $this->assertContains("sohu",$res);
    }
}
