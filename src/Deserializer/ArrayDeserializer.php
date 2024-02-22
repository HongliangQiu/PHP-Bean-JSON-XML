<?php

namespace PHPBean\Deserializer;

use PHPBean\Utils\ClassPropertyInfo;

class ArrayDeserializer extends Deserializer
{
    public static function deserialize(mixed $targetValue, ClassPropertyInfo $classPropertyInfo, int $listDimension = 0): ?array
    {
        if (is_array($targetValue)) {
            return $targetValue;
        }

        return null;
    }
}