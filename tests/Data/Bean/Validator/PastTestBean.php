<?php

namespace Data\Bean\Validator;

use Data\BeanInstance;
use PHPBean\Attributes\Validator\Past;

class PastTestBean implements BeanInstance
{
    #[Past("past date time expect")]
    public string $pastDate;

    public static function getInstance(string $value = "9999-02-23 14:32:15"): object
    {
        $pastTestBean = new PastTestBean();
        $pastTestBean->pastDate = $value;
        return $pastTestBean;
    }

    public static function getJsonString(string $value = "9999-02-23 14:32:15"): string
    {
        return json_encode(self::getInstance($value));
    }
}
