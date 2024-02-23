<?php

namespace Data\Bean\Validator;

use Data\BeanInstance;
use PHPBean\Attributes\Validator\AssertFalse;

class AssertFalseTestBean implements BeanInstance
{
    #[AssertFalse("false expect")]
    public bool $assertFalse = true;

    public static function getInstance(bool $value = true): object
    {
        $assertFalseTestBean = new AssertFalseTestBean();
        $assertFalseTestBean->assertFalse = $value;
        return $assertFalseTestBean;
    }

    public static function getJsonString(bool $value = true): string
    {
        return json_encode(self::getInstance($value));
    }
}
