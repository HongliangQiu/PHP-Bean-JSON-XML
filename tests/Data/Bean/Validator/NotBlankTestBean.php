<?php

namespace Data\Bean\Validator;

use Data\BeanInstance;
use PHPBean\Attributes\Validator\NotBlank;

class NotBlankTestBean implements BeanInstance
{
    #[NotBlank("can not be blank")]
    public string $value = '';

    public static function getInstance(): object
    {
        return new NotBlankTestBean();
    }

    public static function getJsonString(): string
    {
        return json_encode(self::getInstance());
    }
}
