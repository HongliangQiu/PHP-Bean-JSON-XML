<?php

require(__DIR__ . "/autoload.php");

use Data\Bean\GetterSetterBean;
use PHPBean\Exception\PHPBeanException;
use PHPBean\JSON;
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