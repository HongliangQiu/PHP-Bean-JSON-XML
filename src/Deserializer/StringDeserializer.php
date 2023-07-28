<?php

namespace PHPBean\Deserializer;

use PHPBean\Utils\ClassPropertyInfo;

class StringDeserializer extends Deserializer
{
    public static function deserialize(mixed $targetValue, ClassPropertyInfo $classPropertyInfo, int $listDimension = 0): ?string
    {
        if (is_string($targetValue)) {
            return $targetValue;
        }
        if (is_scalar($targetValue)) {
            return strval($targetValue);
        }
        if (is_object($targetValue) && method_exists($targetValue, '__toString')) {
            return strval($targetValue);
        }

        return null;
    }
}