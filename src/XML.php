<?php

namespace PHPBean;

use Exception;
use PHPBean\Exception\PHPBeanException;
use ReflectionException;
use SimpleXMLElement;

/**
 * @template T
 */
class XML
{
    // todo 【新功能】额外增加一些其他的常用方法，便于使用，比如 safe_simplexml_* 就是 getSimpleXMLElement

    /**
     * Parse a xml string, the result is an object which is an instance of {className}.
     *
     * @param string|null $xmlString
     * @param class-string<T> $className
     * @return T|null
     * @throws PHPBeanException
     */
    public static function parseObj(?string $xmlString, string $className): ?object
    {
        $simpleXMLElement = self::getSimpleXMLElement($xmlString);
        try {
            return ObjectToBean::parseObj($simpleXMLElement, $className);
        } catch (ReflectionException $e) {
            throw new PHPBeanException($e);
        }
    }

    /**
     * @param string|null $xmlString
     * @return SimpleXMLElement
     * @throws PHPBeanException
     */
    private static function getSimpleXMLElement(?string $xmlString): SimpleXMLElement
    {
        $libxml_use_internal_errors = libxml_use_internal_errors();
        // Suppress E_WARNING error message, use libxml_get_errors to get all error.
        libxml_use_internal_errors(true);

        try {
            return new SimpleXMLElement($xmlString, LIBXML_NOCDATA);
        } catch (Exception $e) {
            $error = libxml_get_errors()[0];
            throw new PHPBeanException($e->getMessage() . ',' . $error->message);
        } finally {
            // Restore the config, do not impact the outer program.
            libxml_use_internal_errors($libxml_use_internal_errors);
        }
    }
}
