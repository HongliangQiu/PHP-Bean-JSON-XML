<?php

namespace PHPBean\Deserializer;

use PHPBean\Utils\ClassPropertyInfo;
use SimpleXMLElement;

class IntDeserializer extends Deserializer
{
    public static function deserialize(mixed $targetValue, ClassPropertyInfo $classPropertyInfo, int $listDimension = 0): ?int
    {
        if (is_int($targetValue)) {
            return $targetValue;
        }
        if ($targetValue instanceof SimpleXMLElement) {
            $targetValue = $targetValue->__toString();
        }
        if (is_numeric($targetValue)) {
            return intval($targetValue);
        }

        return null;
    }
}