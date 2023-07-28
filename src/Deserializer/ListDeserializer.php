<?php

namespace PHPBean\Deserializer;

use PHPBean\Exception\PHPBeanException;
use PHPBean\Utils\ClassPropertyInfo;
use ReflectionException;

class ListDeserializer extends Deserializer
{
    /**
     * @throws ReflectionException|PHPBeanException
     */
    public static function deserialize(mixed $targetValue, ClassPropertyInfo $classPropertyInfo, int $listDimension = 0): ?array
    {
        if ((!is_array($targetValue)) || (!array_is_list($targetValue))) {
            // If not a list.
            return null;
        }

        $valueList = [];
        // It is one-dimensional array now.
        if ($listDimension <= 1) {
            foreach ($targetValue as $value) {
                $valueList[] = MixValueDeserializer::deserialize($value, $classPropertyInfo, 0);
            }
            return $valueList;
        }

        // It is multidimensional array now.
        $listDimension = $listDimension - 1;
        foreach ($targetValue as $arrayList) {
            if ((!is_array($arrayList)) || (!array_is_list($arrayList))) {
                continue;
            }
            $valueList[] = self::deserialize($arrayList, $classPropertyInfo, $listDimension);
        }
        return $valueList;
    }
}