<?php

namespace PHPBeanTest;

require("../autoload.php");

use PHPBean\Exception\PHPBeanException;
use PHPBean\JSON;
use PHPBeanTest\Data\Bean\GetterSetterBean;
use PHPUnit\Framework\TestCase;

/**
 * @test
 */
class JSONGetterSetterTest extends TestCase
{
    /**
     * Test for bean class
     *
     * @return void
     * @throws PHPBeanException
     */
    public function testSetter()
    {
        $setterBean = GetterSetterBean::getInstance();
        $jsonString = GetterSetterBean::getJsonString();
        $parseObj = JSON::parseObj($jsonString, GetterSetterBean::class);
        self::assertEquals($setterBean, $parseObj);
    }
}