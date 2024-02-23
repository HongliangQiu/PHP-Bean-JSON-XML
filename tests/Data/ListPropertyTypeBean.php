<?php

namespace Data;

use PHPBean\Attributes\ListPropertyType;
use PHPBean\Enum\TypeName;

class ListPropertyTypeBean implements BeanInstance
{
    /**
     * 1 dimensional array
     *
     * @var int[]
     */
    #[ListPropertyType(TypeName::INT)]
    public array $oneArray = [1, 2, 3];

    /**
     * 2 dimensional array
     *
     * @var int[][]
     */
    #[ListPropertyType(TypeName::INT, 2)]
    public array $twoDimensionalArray = [
        [1, 2, 3],
        [4, 5, 6],
    ];

    public static function getInstance(): object
    {
        return new ListPropertyTypeBean();
    }

    public static function getJsonString(): string
    {
        return json_encode(self::getInstance());
    }
}