<?php
use PtPHP\UnitTest as UnitTest;
class TestLuosimao extends UnitTest
{

    protected function setUp()
    {
    }

    public function testSend()
    {
        $res = Model_Tools_Luosimao::send_captcha("18601628937","123568");
        var_export($res);
    }
}
