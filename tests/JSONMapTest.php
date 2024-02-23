<?php

require(__DIR__ . "/autoload.php");

use Data\Bean\OrderBean;
use Data\SimpleMap\SimpleListBean;
use Data\SimpleMap\SimpleMapBean;
use PHPBean\Exception\PHPBeanException;
use PHPBean\JSON;
use PHPUnit\Framework\TestCase;

/**
 * @test
 */
class JSONMapTest extends TestCase
{
    /**
     * Test for very simple JSON MAP {}
     *
     * @return void
     * @throws PHPBeanException
     */
    public function testSimpleMap()
    {
        $simpleMapData = SimpleMapBean::getInstance();
        $jsonString = SimpleMapBean::getJsonString();
        $parseObj = JSON::parseObj($jsonString, SimpleMapBean::class);
        self::assertEquals($simpleMapData, $parseObj);
    }

    /**
     * Test for very simple JSON Element List
     *
     * @return void
     * @throws PHPBeanException
     */
    public function testSimpleListElement()
    {
        $simpleListData = SimpleListBean::getInstance();
        $jsonString = SimpleListBean::getJsonString();
        $parseObj = JSON::parseObj($jsonString, SimpleListBean::class);
        self::assertEquals($simpleListData, $parseObj);
    }

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