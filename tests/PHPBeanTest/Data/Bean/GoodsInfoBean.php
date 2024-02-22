<?php

namespace PHPBeanTest\Data\Bean;

class GoodsInfoBean
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
}