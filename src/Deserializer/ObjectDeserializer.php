<?php

namespace PHPBean\Deserializer;

use PHPBean\Utils\ClassPropertyInfo;

class ObjectDeserializer extends Deserializer
{
    public static function deserialize(mixed $targetValue, ClassPropertyInfo $classPropertyInfo, int $listDimension = 0): ?object
    {
        if (is_object($targetValue)) {
            return $targetValue;
        }

        return null;
    }
}