<?php

require(__DIR__ . "/autoload.php");

use Data\Bean\PropertyAliasBean;
use PHPBean\Exception\PHPBeanException;
use PHPBean\JSON;
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