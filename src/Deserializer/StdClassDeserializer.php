<?php

namespace PHPBean\Deserializer;

use PHPBean\Utils\ClassPropertyInfo;
use stdClass;

class StdClassDeserializer extends Deserializer
{
    public static function deserialize(mixed $targetValue, ClassPropertyInfo $classPropertyInfo, int $listDimension = 0): ?stdClass
    {
        if ($targetValue instanceof stdClass) {
            return $targetValue;
        }
        if (is_object($targetValue)) {
            return json_decode(json_encode($targetValue));
        }

        return null;
    }
}