<?php

namespace PHPBean\Deserializer;

use PHPBean\Utils\ClassPropertyInfo;

class ArrayDeserializer extends Deserializer
{
    // todo 测试类型为 stdClass 如何返回
    public static function deserialize(mixed $targetValue, ClassPropertyInfo $classPropertyInfo, int $listDimension = 0): ?array
    {
        if (is_array($targetValue)) {
            return $targetValue;
        }

        return null;
    }
}