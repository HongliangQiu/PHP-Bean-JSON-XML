<?php

namespace Data\Bean\Validator;

use Data\BeanInstance;
use PHPBean\Attributes\Validator\Future;

class FutureTestBean implements BeanInstance
{
    #[Future("future date time expect")]
    public string $futureDate;

    public static function getInstance(string $value = "9999-02-23 14:32:15"): object
    {
        $futureTestBean = new FutureTestBean();
        $futureTestBean->futureDate = $value;
        return $futureTestBean;
    }

    public static function getJsonString(string $value = "9999-02-23 14:32:15"): string
    {
        return json_encode(self::getInstance($value));
    }
}
