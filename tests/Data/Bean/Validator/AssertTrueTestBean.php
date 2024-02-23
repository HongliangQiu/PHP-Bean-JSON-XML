<?php

namespace Data\Bean\Validator;

use Data\BeanInstance;
use PHPBean\Attributes\Validator\AssertTrue;

class AssertTrueTestBean implements BeanInstance
{
    #[AssertTrue("true expect")]
    public bool $assertTrue = false;

    public static function getInstance(bool $value = false): object
    {
        $assertTrueTestBean = new AssertTrueTestBean();
        $assertTrueTestBean->assertTrue = $value;
        return $assertTrueTestBean;
    }

    public static function getJsonString(bool $value = true): string
    {
        return json_encode(self::getInstance($value));
    }
}
