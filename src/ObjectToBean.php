<?php

namespace PHPBean;

use PHPBean\Deserializer\MixValueDeserializer;
use PHPBean\Exception\PHPBeanException;
use PHPBean\Utils\ClassInfoCache;
use PHPBean\Utils\ClassUtil;
use ReflectionException;

// todo 检查不应该存在的 " 字符，用 ' 替换，报错除外。
// todo 删除所有中文信息注释、报错，检查是否存在无效代码
// todo 当前的 classInfoCache 替换成 config。

/**
 * @template T
 * @author hl_qiu163@163.com
 * @package ObjectToBean
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
            throw new PHPBeanException("The {srcList} is not a list.");
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
     * 解析一个对象
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
     * 这个方法不能被递归调用，因为其中的 getListDimension，否则多维数组无法正常使用
     *
     * @param class-string<T> $className
     * @return T
     * @throws ReflectionException|PHPBeanException
     */
    private static function parseMixValue(mixed $srcObject, string $className): object
    {
        $reflectionClass = ClassUtil::getReflectionClass($className);
        $targetBeanInstance = ClassUtil::createNewInstance($reflectionClass->name);

        /** before handle @see ClassInfoCache::initClassPropertyInfoCache */

        // 从缓存中获取类的所有信息
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

