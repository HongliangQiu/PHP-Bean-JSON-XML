<?php

namespace PHPBeanTest\Data;

use PHPBean\Attributes\ListPropertyType;
use PHPBean\Attributes\PropertyAlias;
use PHPBean\Enum\TypeName;

class OrderBean
{
    #[PropertyAlias('order_no')]
    public string $orderNo;

    public ?OrderInfoBean $orderInfo;

    /**
     * @var $goodsList GoodsInfoBean[]
     */
    #[ListPropertyType(GoodsInfoBean::class)]
    public array $goodsList;

    /**
     * @var $snList string[]
     */
    // 普通数组测试
    #[ListPropertyType(TypeName::STRING)]
    public ?array $snList;

    /**
     * @var array[]
     */
    // 普通数组测试
    // #[ListPropertyType(GoodsInfoBean::class, 2)]
    // public ?array $multiDimensionalList;

    /**
     * @param string|null $orderNo
     */
    public function setOrderNo(?string $orderNo): void
    {
        $this->orderNo = $orderNo;
    }

    /**
     * @param OrderInfoBean|null $orderInfo
     */
    public function setOrderInfo(?OrderInfoBean $orderInfo): void
    {
        $this->orderInfo = $orderInfo;
    }

    /**
     * @param array|null $goodsList
     */
    public function setGoodsList(?array $goodsList): void
    {
        $this->goodsList = $goodsList;
    }

    /**
     * @param array|null $snList
     */
    public function setSnList(?array $snList): void
    {
        $this->snList = $snList;
    }

    /**
     * @return array|null
     */
    public function getMultiDimensionalList(): ?array
    {
        return $this->multiDimensionalList;
    }

    /**
     * @param array|null $multiDimensionalList
     */
    public function setMultiDimensionalList(?array $multiDimensionalList): void
    {
        $this->multiDimensionalList = $multiDimensionalList;
    }
}