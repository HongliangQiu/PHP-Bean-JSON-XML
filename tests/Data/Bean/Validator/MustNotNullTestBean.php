<?php

namespace Data\Bean\Validator;

use Data\BeanInstance;
use PHPBean\Attributes\Validator\MustNotNull;

class MustNotNullTestBean implements BeanInstance
{
    #[MustNotNull("can not be null")]
    public ?bool $value = null;

    public static function getInstance(): object
    {
        return new MustNotNullTestBean();
    }

    public static function getJsonString(): string
    {
        return json_encode(self::getInstance());
    }
}
