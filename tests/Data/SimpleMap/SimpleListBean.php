<?php

namespace Data\SimpleMap;

use Data\BeanInstance;
use PHPBean\Attributes\ListPropertyType;
use PHPBean\Enum\TypeName;
use TestUtil;

class SimpleListBean implements BeanInstance
{
    #[ListPropertyType(TypeName::NULL)]
    public array $vNullList;
    #[ListPropertyType(TypeName::STRING)]
    public array $vStringList;
    #[ListPropertyType(TypeName::BOOL)]
    public array $vBoolList;
    #[ListPropertyType(TypeName::BOOL)]
    public array $vTrueList;
    #[ListPropertyType(TypeName::BOOL)]
    public array $vFalseList;
    #[ListPropertyType(TypeName::BOOL)]
    public array $vBooleanList;
    #[ListPropertyType(TypeName::INT)]
    public array $vIntList;
    #[ListPropertyType(TypeName::INT)]
    public array $vIntegerList;
    #[ListPropertyType(TypeName::FLOAT)]
    public array $vFloatList;
    #[ListPropertyType(TypeName::FLOAT)]
    public array $vDoubleList;
    #[ListPropertyType(TypeName::ARRAY)]
    public array $vArrayList;
    #[ListPropertyType(TypeName::OBJECT)]
    public array $vObjectList;
    #[ListPropertyType(TypeName::STDCLASS)]
    public array $vStdClassList;

    public static function getInstance(): SimpleListBean
    {
        $simpleListData = new SimpleListBean();
        $simpleListData->vNullList = [null, null, null];
        $simpleListData->vStringList = ['string', 'string2'];
        $simpleListData->vBoolList = [false, true];
        $simpleListData->vTrueList = [true, false];
        $simpleListData->vFalseList = [false, true];
        $simpleListData->vBooleanList = [true, false];
        $simpleListData->vIntList = [10, 1.0];
        $simpleListData->vIntegerList = [-1, 0, 1];
        $simpleListData->vFloatList = [1.234, 1];
        $simpleListData->vDoubleList = [1.3456789, 789];
        $simpleListData->vArrayList = [[]];
        $simpleListData->vObjectList = [TestUtil::makeStdClass(["a" => "a"])];
        $simpleListData->vStdClassList = [TestUtil::makeStdClass(["b" => "b"])];

        return $simpleListData;
    }

    public static function getJsonString(): string
    {
        return json_encode(self::getInstance(), JSON_UNESCAPED_SLASHES);
    }
}