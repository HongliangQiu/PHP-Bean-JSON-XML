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
class JSONListTest extends TestCase
{
    /**
     * Test for very simple JSON List "[{}]"
     *
     * @return void
     * @throws PHPBeanException
     */
    public function testSimpleList()
    {
        $simpleMapData = SimpleMapBean::getInstance();
        $list = [$simpleMapData, $simpleMapData];
        $jsonString = json_encode($list);

        $parseList = JSON::parseList($jsonString, SimpleMapBean::class);
        self::assertEquals($list, $parseList);
    }

}