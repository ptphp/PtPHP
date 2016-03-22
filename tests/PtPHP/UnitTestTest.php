<?php

class UnitTestTest extends PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
    }

    public function testAddTestSuite()
    {
        PtPHP\print_pre(1);
        $t = new PtPHP\UnitTest();
        $t->test();
        $this->assertEquals(1, 1);
    }
}
