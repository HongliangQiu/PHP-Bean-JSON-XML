<?php

namespace PHPBeanTest\Data\Bean;

use PHPBean\Attributes\ListPropertyType;

class OrderBean
{
    public string $orderNo;

    public OrderInfoBean $orderInfo;

    /**
     * @var $goodsList GoodsInfoBean[]
     */
    #[ListPropertyType(GoodsInfoBean::class)]
    public array $goodsList;

    public static function getInstance(): OrderBean
    {
        $orderBean = new OrderBean();
        $orderBean->orderNo = "orderNo:12345";
        $orderBean->orderInfo = OrderInfoBean::getInstance();
        $orderBean->goodsList = [GoodsInfoBean::getInstance(), GoodsInfoBean::getInstance()];
        return $orderBean;
    }

    public static function getJsonString(): string
    {
        return json_encode(self::getInstance());
    }
}