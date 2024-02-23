<?php

namespace Data\Bean\Validator;

use Data\BeanInstance;
use PHPBean\Attributes\Validator\Pattern;

class PatternTestBean implements BeanInstance
{
    #[Pattern("/\d{11}/", "pattern not match")]
    public string $value = "12345";

    public static function getInstance(): object
    {
        return new PastTestBean();
    }

    public static function getJsonString(): string
    {
        return json_encode(self::getInstance());
    }
}
