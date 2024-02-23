<?php

namespace Data\Bean;

use Data\BeanInstance;

class OrderInfoBean implements BeanInstance
{
    public ?int $goodsCount;
    public ?bool $isCod;
    public ?float $amount;
    public ?string $ownerNo;

    public static function getInstance(): OrderInfoBean
    {
        $orderInfoBean = new OrderInfoBean();

        $orderInfoBean->goodsCount = 123;
        $orderInfoBean->isCod = true;
        $orderInfoBean->amount = 1.2345;
        $orderInfoBean->ownerNo = "ownerNo";

        return $orderInfoBean;
    }

    public static function getJsonString(): string
    {
        return json_encode(self::getInstance());
    }
}