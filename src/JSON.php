<?php

namespace PHPBean;

use Exception;
use PHPBean\Exception\PHPBeanException;
use PHPBean\Exception\PHPBeanExceptionHandler;

// todo 增加【配置】默认值选项，如果不存在是否执行默认初始化默认值操作

/**
 * @template T
 */
class JSON
{
    // todo 【新功能】额外增加一些其他的常用方法，便于使用

    /**
     * Parse a json string, the result is a list of object which is an instance of {className}.
     *
     * @param string|null $jsonStr
     * @param class-string<T> $className
     * @param PHPBeanExceptionHandler|null $PHPBeanExceptionHandle The exception handler. If supplies it, will execute it rather than throw an exception.
     * @return T[]|null
     * @throws PHPBeanException
     */
    public static function parseList(?string $jsonStr, string $className, PHPBeanExceptionHandler $PHPBeanExceptionHandle = null): ?array
    {
        try {
            $list = json_decode($jsonStr, false, 512, JSON_THROW_ON_ERROR);
            if (!array_is_list($list)) {
                throw new PHPBeanException("JSON::parseList failure: the json data is not a list. Please check your json string.");
            }
            return ObjectToBean::parseList($list, $className);
        } catch (Exception $e) {
            self::dealException($e, $PHPBeanExceptionHandle);
            return null;
        }
    }

    /**
     * Parse a json string, the result is an object which is an instance of {className}.
     *
     * @param string|null $jsonStr
     * @param class-string<T> $className
     * @param PHPBeanExceptionHandler|null $PHPBeanExceptionHandle The exception handler. If supplies it, will execute it rather than throw an exception.
     * @return T|null
     * @throws PHPBeanException
     */
    public static function parseObj(?string $jsonStr, string $className, PHPBeanExceptionHandler $PHPBeanExceptionHandle = null): ?object
    {
        try {
            $object = json_decode($jsonStr, false, 512, JSON_THROW_ON_ERROR);
            return ObjectToBean::parseObj($object, $className);
        } catch (Exception $e) {
            self::dealException($e, $PHPBeanExceptionHandle);
            return null;
        }
    }

    /**
     * @throws PHPBeanException
     */
    private static function dealException(Exception $exception, PHPBeanExceptionHandler $PHPBeanExceptionHandle = null): void
    {
        assert($exception != null);
        $PHPBeanException = new PHPBeanException($exception->getMessage());
        if ($PHPBeanExceptionHandle === null) {
            throw $PHPBeanException;
        }
        $PHPBeanExceptionHandle->execute($PHPBeanException);
    }
}