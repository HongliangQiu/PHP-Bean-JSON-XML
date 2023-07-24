<?php

namespace PHPBean\Deserializer;

use PHPBean\Utils\ClassPropertyInfo;

abstract class Deserializer
{
    /**
     * Deserialize the target value, then return the result.
     *
     * @param mixed $targetValue Current value from source data (eg. json field value).
     * @param ClassPropertyInfo $classPropertyInfo
     * @param int $listDimension The array list dimension. The parameter is only useful for array list.
     * @return mixed The result which could be null.
     */
    abstract public static function deserialize(mixed $targetValue, ClassPropertyInfo $classPropertyInfo, int $listDimension = 0): mixed;
}