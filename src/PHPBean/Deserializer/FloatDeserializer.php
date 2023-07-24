<?php

namespace PHPBean\Deserializer;

use PHPBean\Utils\ClassPropertyInfo;
use SimpleXMLElement;

class FloatDeserializer extends Deserializer
{
    public static function deserialize(mixed $targetValue, ClassPropertyInfo $classPropertyInfo, int $listDimension = 0): ?float
    {
        if (is_float($targetValue)) {
            return $targetValue;
        }
        if ($targetValue instanceof SimpleXMLElement) {
            $targetValue = $targetValue->__toString();
        }
        if (is_numeric($targetValue)) {
            return floatval($targetValue);
        }

        return null;
    }
}