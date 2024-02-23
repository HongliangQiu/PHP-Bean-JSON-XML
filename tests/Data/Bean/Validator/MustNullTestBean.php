<?php

namespace Data\Bean\Validator;

use Data\BeanInstance;
use PHPBean\Attributes\Validator\MustNull;

class MustNullTestBean implements BeanInstance
{
    #[MustNull("can only be null")]
    public ?bool $value = true;

    public static function getInstance(): object
    {
        return new MustNullTestBean();
    }

    public static function getJsonString(): string
    {
        return json_encode(self::getInstance());
    }
}
