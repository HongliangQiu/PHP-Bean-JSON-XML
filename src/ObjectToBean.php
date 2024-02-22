<?php

namespace PHPBean;

use PHPBean\Deserializer\MixValueDeserializer;
use PHPBean\Exception\PHPBeanException;
use PHPBean\Utils\ClassInfoCache;
use PHPBean\Utils\ClassUtil;
use ReflectionException;

/**
 * @template T
 * @author hl_qiu163@163.com
 */
class ObjectToBean
{
    /**
     * Parse an object list.
     *
     * @param object[] $srcObjectList
     * @param class-string<T> $className
     * @return T[]
     * @throws ReflectionException|PHPBeanException
     */
    public static function parseList(array $srcObjectList, string $className): array
    {
        if (!array_is_list($srcObjectList)) {
            throw new PHPBeanException('The {srcList} is not a list.');
        }

        $targetValueList = [];
        foreach ($srcObjectList as $index => $srcObj) {
            if (!is_object($srcObj)) {
                throw new PHPBeanException("Parse list failure: the {$index}th element is not an object.");
            }
            $targetValueList[] = self::parseMixValue($srcObj, $className);
        }
        return $targetValueList;
    }

    /**
     * Deserialize one object
     *
     * @param object $srcObject
     * @param class-string<T> $className
     * @return T
     * @throws PHPBeanException
     * @throws ReflectionException
     */
    public static function parseObj(object $srcObject, string $className): object
    {
        return self::parseMixValue($srcObject, $className);
    }

    /**
     * @param class-string<T> $className
     * @return T
     * @throws ReflectionException|PHPBeanException
     */
    private static function parseMixValue(mixed $srcObject, string $className): object
    {
        $reflectionClass = ClassUtil::getReflectionClass($className);
        $targetBeanInstance = ClassUtil::createNewInstance($reflectionClass->name);

        /** before handle @see ClassInfoCache::initClassPropertyInfoCache */

        $classPropertyInfos = ClassInfoCache::getAllProperties($reflectionClass);

        // Traverse the bean class
        foreach ($classPropertyInfos as $classPropertyInfo) {
            $targetValue = ClassUtil::getFieldValue($srcObject, $classPropertyInfo);

            $propertyMixValue = MixValueDeserializer::deserialize($targetValue, $classPropertyInfo, $classPropertyInfo->getListDimension());

            /** after handle @see ClassUtil::setPropertyValue */
            ClassUtil::setPropertyValue($targetBeanInstance, $classPropertyInfo, $propertyMixValue);
        }

        return $targetBeanInstance;
    }
}

