<?php

namespace Data\Bean\Validator;

use Data\BeanInstance;
use PHPBean\Attributes\Validator\Length;

class LengthTestBean implements BeanInstance
{
    #[Length(3, "string value max length 3")]
    public string $strValue;
    #[Length(2, "array value max length 2")]
    public array $arrayValue;

    public static function getInstance(string $strValue = '', array $arrayValue = []): object
    {
        $lengthTestBean = new LengthTestBean();
        $lengthTestBean->strValue = $strValue;
        $lengthTestBean->arrayValue = $arrayValue;
        return $lengthTestBean;
    }

    public static function getJsonString(string $strValue = '', array $arrayValue = []): string
    {
        return json_encode(self::getInstance($strValue, $arrayValue));
    }
}
