<?php

namespace PHPBean\Utils;

use PHPBean\Exception\PHPBeanException;
use ReflectionClass;
use ReflectionException;

class ClassInfoCache
{
    /**
     * Cache all properties of the class.
     *
     * @var array<string, array<string,ClassPropertyInfo>> key = Class Name; value = list<{Class property info}>
     */
    private static array $classPropertyInfoListMapCache = [];

    /**
     * Get properties of the class.
     *
     * @param ReflectionClass $reflectionClass
     * @return array<string,ClassPropertyInfo>
     * @throws PHPBeanException|ReflectionException
     */
    public static function getAllProperties(ReflectionClass $reflectionClass): array
    {
        $cacheKey = $reflectionClass->name;
        if (array_key_exists($cacheKey, ClassInfoCache::$classPropertyInfoListMapCache)) {
            return self::$classPropertyInfoListMapCache[$cacheKey];
        }

        // If not exists, then load it.
        self::initClassPropertyInfoCache($reflectionClass);
        return self::$classPropertyInfoListMapCache[$cacheKey];
    }

    /**
     * @throws ReflectionException|PHPBeanException
     */
    private static function initClassPropertyInfoCache(ReflectionClass $reflectionClass): void
    {
        $className = $reflectionClass->name;
        $classPropertyInfos = ClassUtil::getClassPropertiesInfo($reflectionClass);
        if (empty($classPropertyInfos)) {
            self::$classPropertyInfoListMapCache[$className] = [];
            return;
        }
        foreach ($classPropertyInfos as $classPropertyInfo) {
            $propertyName = $classPropertyInfo->propertyName;

            // before handle, maybe it will change the $classPropertyInfo.
            ClassUtil::executeBeforeHandleAttribute($reflectionClass, $classPropertyInfo);

            // put cache
            ClassInfoCache::$classPropertyInfoListMapCache[$className][$propertyName] = $classPropertyInfo;
        }
    }
}