<?php

namespace PHPBean\Deserializer;

use PHPBean\Enum\TypeName;
use PHPBean\Exception\PHPBeanException;
use PHPBean\Utils\ClassPropertyInfo;
use ReflectionException;

class MixValueDeserializer extends Deserializer
{
    /**
     * @throws ReflectionException|PHPBeanException
     */
    public static function deserialize(mixed $targetValue, ClassPropertyInfo $classPropertyInfo, int $listDimension = 0): mixed
    {
        if ($classPropertyInfo->isListType && $listDimension > 0) {
            return ListDeserializer::deserialize($targetValue, $classPropertyInfo, $listDimension);
        }

        return match ($classPropertyInfo->getPropertyType()) {
            TypeName::STRING => StringDeserializer::deserialize($targetValue, $classPropertyInfo, $listDimension),
            TypeName::INT, TypeName::INTEGER => IntDeserializer::deserialize($targetValue, $classPropertyInfo, $listDimension),
            TypeName::FLOAT, TypeName::DOUBLE => FloatDeserializer::deserialize($targetValue, $classPropertyInfo, $listDimension),
            TypeName::BOOL, TypeName::BOOLEAN => BoolDeserializer::deserialize($targetValue, $classPropertyInfo, $listDimension),
            TypeName::NULL => null,
            TypeName::TRUE => true,
            TypeName::FALSE => false,
            TypeName::ARRAY => ArrayDeserializer::deserialize($targetValue, $classPropertyInfo, $listDimension),
            TypeName::STDCLASS => StdClassDeserializer::deserialize($targetValue, $classPropertyInfo, $listDimension),
            TypeName::OBJECT => ObjectDeserializer::deserialize($targetValue, $classPropertyInfo, $listDimension),
            default => BeanClassDeserializer::deserialize($targetValue, $classPropertyInfo, $listDimension),
        };
    }
}