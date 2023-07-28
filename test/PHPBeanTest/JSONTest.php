<?php

namespace PHPBeanTest;

require_once("../autoload.php");

use PHPBean\Exception\PHPBeanException;
use PHPBean\JSON;
use PHPBeanTest\Data\GoodsInfoBean;
use PHPBeanTest\Data\OrderBean;
use PHPBeanTest\Data\OrderInfoBean;
use PHPUnit\Framework\TestCase;

/**
 * @test
 */
class JSONTest extends TestCase
{
    private static int $testCount = 1;

    /**
     * @return void
     */
    public function testPHPUnitCost()
    {
        self::assertNotNull([]);
    }

    /**
     * @return void
     */
    public function testJsonDecodeStd()
    {
        // $orderBeanExpect = $this->getOrderBean();
        $orderBeanExpect = array(
            'orderNo'   => '订单号',
            '无效字段'  => '订单号',
            'orderInfo' => array(
                'goodsCount' => 2,
                'isCod'      => 'Y',
                'amount'     => 1.123,
                'ownerNo'    => 'ownerNo',
            ),
            'goodsList' => array(
                ['specNo' => '商家编码0', 'num' => 0,],
                ['specNo' => '商家编码1', 'num' => 1,],
            ),
            'snList'    => array('sn0', 'sn1', 'sn2',),
        );

        $count = self::$testCount;
        $orderBeanResult = null;

        $start = microtime(true);
        $str = json_encode($orderBeanExpect, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        while ($count--) {
            $orderBeanResult = json_decode($str, true);
        }
        $spend = (microtime(true) - $start) * 1000;
        logx("json_decode spend:" . $spend);
        // logx($orderBeanResult, true);

        self::assertNotNull($orderBeanResult);
        // self::assertIsString($orderBeanResult->orderNo);
        // self::assertEquals("订单号", $orderBeanResult->orderNo);
        // self::assertInstanceOf(OrderBean::class, $orderBeanResult);
        //
        // $this->testOrderInfoBean($orderBeanResult->orderInfo);
        // $this->testSnList($orderBeanResult->snList);
        // $this->testGoodsInfoList($orderBeanResult->goodsList);
    }

    /**
     * @return void
     * @throws PHPBeanException
     */
    public function testOrderBean()
    {
        // $orderBeanExpect = $this->getOrderBean();
        $orderBeanExpect = array(
            'orderNo'  => '订单号',
            'orderInfo' => array(
                'goodsCount' => 2,
                'isCod'      => 'Y',
                'amount'     => 1.123,
                'owner_no'    => 'ownerNo别名',
                'ownerNo'    => 'ownerNo',
            ),
            'goodsList' => array(
                ['specNo' => '商家编码0', 'num' => 0,],
                ['specNo' => '商家编码1', 'num' => 1,],
            ),
            'snList'    => array('sn0', 'sn1', 'sn2',),
        );

        $count = self::$testCount;
        $orderBeanResult = null;
        $start = microtime(true);
        $str = json_encode($orderBeanExpect, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        while ($count--) {
            $orderBeanResult = JSON::parseObj($str, OrderBean::class);
        }
        $spend = (microtime(true) - $start) * 1000;
        logx("JSON::parseObj spend:" . $spend);
        logx($orderBeanResult);
        // logx($str);

        self::assertNotNull($orderBeanResult);
        self::assertIsString($orderBeanResult->orderNo);
        self::assertEquals("订单号", $orderBeanResult->orderNo);
        self::assertInstanceOf(OrderBean::class, $orderBeanResult);

        $this->testOrderInfoBean($orderBeanResult->orderInfo);
        $this->testSnList($orderBeanResult->snList);
        $this->testGoodsInfoList($orderBeanResult->goodsList);
    }

    /**
     * @throws PHPBeanException
     */
    public function testParseList()
    {
        $goodsList = array(
            ['specNo' => '商家编码0', 'num' => 0,],
            ['specNo' => '商家编码1', 'num' => 1,],
        );
        $str = json_encode($goodsList, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $goodsInfoBeans = JSON::parseList($str, GoodsInfoBean::class);
        fwrite(STDOUT, print_r($goodsInfoBeans, true));
        self::assertIsList($goodsInfoBeans);
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

    private function getOrderBean(): OrderBean
    {
        $orderInfoBean = new OrderInfoBean();

        $orderInfoBean->goodsCount = 2;
        $orderInfoBean->isCod = 'Y';
        $orderInfoBean->amount = 1.123;
        $orderInfoBean->ownerNo = "ownerNo";

        $goodsListBean = new GoodsInfoBean();
        $goodsListBean->num = 0;
        $goodsListBean->specNo = "商家编码0";
        $goodsList[] = $goodsListBean;

        $goodsListBean = new GoodsInfoBean();
        $goodsListBean->num = 1;
        $goodsListBean->specNo = "商家编码1";
        $goodsList[] = $goodsListBean;

        $orderBean = new OrderBean();
        $orderBean->orderNo = "订单号";
        $orderBean->orderInfo = $orderInfoBean;
        // orderBean->orderInfo = $ord;
        $orderBean->snList = ['sn0', 'sn1', 'sn2',];
        $orderBean->goodsList = $goodsList;

        return $orderBean;
    }
}

function logx($msg): void
{
    if ($msg === null) {
        $msg = 'null';
    }
    fwrite(STDOUT, print_r($msg, true) . "\r\n");
}