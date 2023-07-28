<?php

namespace PHPBean\Deserializer;

use PHPBean\Exception\PHPBeanException;
use PHPBean\Utils\ClassInfoCache;
use PHPBean\Utils\ClassPropertyInfo;
use PHPBean\Utils\ClassUtil;
use ReflectionException;

class BeanClassDeserializer extends Deserializer
{
    /**
     * @throws ReflectionException|PHPBeanException
     */
    public static function deserialize(mixed $targetValue, ClassPropertyInfo $classPropertyInfo, int $listDimension = 0): ?object
    {
        if (!is_object($targetValue)) {
            return null;
        }

        $reflectionClass = ClassUtil::getReflectionClass($classPropertyInfo->getPropertyType());

        /** before handle @see ClassInfoCache::initClassPropertyInfoCache */
        // Get all info from cache.
        $classPropertyInfos = ClassInfoCache::getAllProperties($reflectionClass);

        $targetBeanInstance = ClassUtil::createNewInstance($reflectionClass->name);

        // Traverse the bean class
        foreach ($classPropertyInfos as $tmpClassPropertyInfo) {
            $tmpValue = ClassUtil::getFieldValue($targetValue, $tmpClassPropertyInfo);

            $propertyMixValue = MixValueDeserializer::deserialize($tmpValue, $tmpClassPropertyInfo, $tmpClassPropertyInfo->getListDimension());

            /** after handle @see self::setPropertyValue */
            ClassUtil::setPropertyValue($targetBeanInstance, $tmpClassPropertyInfo, $propertyMixValue);
        }

        return $targetBeanInstance;
    }
}