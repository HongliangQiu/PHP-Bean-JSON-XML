<?php

namespace Data\Bean;

use Data\BeanInstance;
use PHPBean\Attributes\PropertyAlias;
use stdClass;

class PropertyAliasBean implements BeanInstance
{
    #[PropertyAlias("spec_no")]
    public ?string $specNo;
    #[PropertyAlias("goods_count")]
    public ?float $goodsCount;

    /**
     * @return object{spec_no:string, goods_count:float}
     */
    public static function getInstance(): object
    {
        $stdClass = new stdClass();
        $stdClass->spec_no = "spec_no";
        $stdClass->goods_count = 123;
        return $stdClass;
    }

    public static function getJsonString(): string
    {
        $stdClass = self::getInstance();
        return json_encode($stdClass);
    }
}