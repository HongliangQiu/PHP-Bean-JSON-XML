<?php

namespace PHPBeanTest;

require("../autoload.php");

use PHPBean\Exception\PHPBeanException;
use PHPBean\JSON;
use PHPBeanTest\Data\Bean\GoodsInfoBean;
use PHPBeanTest\Data\Bean\OrderBean;
use PHPBeanTest\Data\Bean\OrderInfoBean;
use PHPBeanTest\Data\SimpleMap\SimpleListBean;
use PHPBeanTest\Data\SimpleMap\SimpleMapBean;
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

    private function createGoodsInfoBean(string $specNo, int $num): GoodsInfoBean
    {
        $goodsInfoBean = new GoodsInfoBean();
        $goodsInfoBean->num = $num;
        $goodsInfoBean->specNo = $specNo;
        return $goodsInfoBean;
    }

    /**
     * test multidimensional array
     *
     * @return void
     * @throws PHPBeanException
     */
    public function testMultidimensional()
    {
        $orderBeanExpect = [
            'multiDimensionalList' => [
                array(
                    $this->createGoodsInfoBean('商家编码0', 0),
                    $this->createGoodsInfoBean('商家编码1', 1),
                ),
                array(
                    $this->createGoodsInfoBean('商家编码2', 2),
                    $this->createGoodsInfoBean('商家编码3', 3),
                ),
            ],
        ];

        $str = json_encode($orderBeanExpect, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $orderBeanResult = JSON::parseObj($str, OrderBean::class);
        fwrite(STDOUT, print_r($orderBeanResult, true));
        self::assertNotNull($orderBeanResult);
    }

    /**
     * 测试 snList
     *
     * @param string[]|null $snList
     * @return void
     */
    private function testSnList(?array $snList): void
    {
        self::assertNotNull($snList);
        self::assertIsList($snList);
        foreach ($snList as $index => $sn) {
            self::assertIsString($sn);
            self::assertEquals("sn{$index}", $sn);
        }
    }

    /**
     * 测试 goodsInfoList
     *
     * @param GoodsInfoBean[]|null $goodsInfoList
     * @return void
     */
    private function testGoodsInfoList(?array $goodsInfoList): void
    {
        self::assertNotNull($goodsInfoList);
        self::assertIsList($goodsInfoList);
        foreach ($goodsInfoList as $index => $goodsInfo) {
            self::assertInstanceOf(GoodsInfoBean::class, $goodsInfo);
            self::assertIsString($goodsInfo->specNo);
            self::assertEquals("商家编码{$index}", $goodsInfo->specNo);
            self::assertIsFloat($goodsInfo->num);
            self::assertEquals($index, $goodsInfo->num);
        }
    }

    /**
     * 测试 OrderInfoBean
     *
     * @param OrderInfoBean|null $orderInfoBean
     * @return void
     */
    private function testOrderInfoBean(?OrderInfoBean $orderInfoBean): void
    {
        self::assertNotNull($orderInfoBean);
        self::assertInstanceOf(OrderInfoBean::class, $orderInfoBean);

        self::assertIsInt($orderInfoBean->goodsCount);
        self::assertEquals(2, $orderInfoBean->goodsCount);

        self::assertIsBool($orderInfoBean->isCod);
        self::assertEquals('Y', $orderInfoBean->isCod);

        self::assertIsFloat($orderInfoBean->amount);
        self::assertEquals(1.123, $orderInfoBean->amount);

        self::assertIsString($orderInfoBean->ownerNo);
        self::assertEquals("ownerNo", $orderInfoBean->ownerNo);
    }
}