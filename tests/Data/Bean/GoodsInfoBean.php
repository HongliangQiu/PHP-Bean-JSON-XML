<?php

namespace Data\Bean;

use Data\BeanInstance;

class GoodsInfoBean implements BeanInstance
{
    public ?string $specNo;
    public ?float $goodsCount;

    public static function getInstance(): GoodsInfoBean
    {
        $goodsInfoBean = new GoodsInfoBean();
        $goodsInfoBean->specNo = "specNo";
        $goodsInfoBean->goodsCount = 123;
        return $goodsInfoBean;
    }

    public static function getJsonString(): string
    {
        return json_encode(self::getInstance());
    }
}