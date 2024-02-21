<?php

namespace PHPBean\Utils;

use PHPBean\Exception\PHPBeanException;
use ReflectionClass;
use ReflectionException;

/**
 * 单次 json_decode 耗时 0.02 毫秒，parseObj 耗时 4 ms。
 */
class ClassInfoCache
{
    /**
     * 缓存一个类的所有字段信息
     *
     * @var array<string, array<string,ClassPropertyInfo>> key = 类名字; value = list<{类的属性（字段）名字, 类内该属性信息}>
     */
    private static array $classPropertyInfoListMapCache = [];

    /**
     * 获取类的缓存信息
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
            // todo 【可能调整】避免空对象，如果修改为 yield 返回数据，那么这个控制需要调整
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