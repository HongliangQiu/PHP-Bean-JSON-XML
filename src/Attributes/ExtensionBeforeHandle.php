<?php

namespace PHPBean\Attributes;

use Exception;
use PHPBean\Utils\ClassPropertyInfo;
use PHPBean\Utils\ClassUtil;
use ReflectionClass;

interface ExtensionBeforeHandle
{
    /**
     * The before handle which after get the class property info. Maybe you can modify the class property by this handle. The result will be cached.
     *
     * @param ReflectionClass $reflectionClass Current class.
     * @param ClassPropertyInfo $classPropertyInfo Current property info which was build by {@see ClassUtil::getClassPropertyInfo()} method
     * @return void
     * @throws Exception
     * @see ListPropertyType for one using case.
     */
    public function beforeHandle(ReflectionClass $reflectionClass, ClassPropertyInfo $classPropertyInfo): void;
}