<?php

namespace PHPBean;

use PHPBean\Exception\PHPBeanException;

// todo 增加【配置】默认值选项，如果不存在是否执行默认初始化默认值操作

/**
 * @template T
 */
class JSON
{
    // todo 【新功能】额外增加一些其他的常用方法，便于使用。增加 json_encode 的封装并增加默认配置选项。 JSON_THROW_ON_ERROR，注意非 UTF8 字符的处理

    /**
     * Parse a json string, the result is a list of object which is an instance of {className}.
     *
     * @param string|null $jsonStr
     * @param class-string<T> $className
     * @return T[]|null
     * @throws PHPBeanException
     */
    public static function parseList(?string $jsonStr, string $className): ?array
    {
        $list = json_decode($jsonStr, false, 512, JSON_THROW_ON_ERROR);
        if (!array_is_list($list)) {
            throw new PHPBeanException("JSON::parseList failure: the json data is not a list. Please check your json string.");
        }
        return ObjectToBean::parseList($list, $className);
    }

    /**
     * Parse a json string, the result is an object which is an instance of {className}.
     *
     * @param string|null $jsonStr
     * @param class-string<T> $className
     * @return T|null
     * @throws PHPBeanException
     */
    public static function parseObj(?string $jsonStr, string $className): ?object
    {
        $object = json_decode($jsonStr, false, 512, JSON_THROW_ON_ERROR);
        return ObjectToBean::parseObj($object, $className);
    }
}