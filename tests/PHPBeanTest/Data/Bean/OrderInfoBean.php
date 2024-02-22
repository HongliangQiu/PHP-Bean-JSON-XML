<?php

namespace PHPBeanTest\Data\Bean;

class OrderInfoBean
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
}