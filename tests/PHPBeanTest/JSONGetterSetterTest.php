<?php

namespace PHPBeanTest;

require("../autoload.php");

use PHPBean\Exception\PHPBeanException;
use PHPBean\JSON;
use PHPBeanTest\Data\Bean\OrderBean;
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
    public function testBeanClass()
    {
        $orderBean = OrderBean::getInstance();
        $jsonString = OrderBean::getJsonString();
        $parseObj = JSON::parseObj($jsonString, OrderBean::class);
        self::assertEquals($orderBean, $parseObj);
    }
}