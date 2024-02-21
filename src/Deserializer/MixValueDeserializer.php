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

        //   todo 【效率优化】考虑将 callable 数据绑定在 classProperty 上，实例化，这样可以不需要做重复的 match 操作。能提高部分操作的 30% 效率。不能采取 getCaller 的方式，会更慢；这里不是效率问题所在
        return match ($classPropertyInfo->getPropertyType()) {
            TypeName::STRING => StringDeserializer::deserialize($targetValue, $classPropertyInfo, $listDimension),
            TypeName::INT, TypeName::INTEGER => IntDeserializer::deserialize($targetValue, $classPropertyInfo, $listDimension),
            TypeName::FLOAT, TypeName::DOUBLE => FloatDeserializer::deserialize($targetValue, $classPropertyInfo, $listDimension),
            TypeName::BOOL, TypeName::BOOLEAN => BoolDeserializer::deserialize($targetValue, $classPropertyInfo, $listDimension),
            TypeName::NULL => null,
            TypeName::TRUE => true,
            TypeName::FALSE => false,
            TypeName::ARRAY => ArrayDeserializer::deserialize($targetValue, $classPropertyInfo, $listDimension),
            // todo add new function 'stdClass'
            default => BeanClassDeserializer::deserialize($targetValue, $classPropertyInfo, $listDimension),
        };
    }
}