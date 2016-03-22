<?php

class FunctionsTest extends PHPUnit_Framework_TestCase
{

    public function testPrintPre()
    {
        $this->assertTrue(PtPHP\print_pre("test"));
    }
}
