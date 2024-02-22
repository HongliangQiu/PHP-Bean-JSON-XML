<?php

namespace PHPBeanTest;

require("../autoload.php");

use PHPBean\Exception\PHPBeanException;
use PHPBean\JSON;
use PHPBeanTest\Data\Bean\PropertyAliasBean;
use PHPUnit\Framework\TestCase;

/**
 * @test
 */
class JSONPropertyAliasTest extends TestCase
{
    /**
     * Test for bean class
     *
     * @return void
     * @throws PHPBeanException
     */
    public function testPropertyAlias()
    {
        $stdClass = PropertyAliasBean::getInstance();
        $jsonString = PropertyAliasBean::getJsonString();
        $parseObj = JSON::parseObj($jsonString, PropertyAliasBean::class);

        self::assertEquals($stdClass->spec_no, $parseObj->specNo);
        self::assertEquals($stdClass->goods_count, $parseObj->goodsCount);
    }
}