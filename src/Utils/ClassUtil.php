<?php

namespace PHPBean\Utils;

use Error;
use PHPBean\Attributes\ExtensionAfterHandle;
use PHPBean\Attributes\ExtensionBeforeHandle;
use PHPBean\Enum\TypeName;
use PHPBean\Exception\PHPBeanException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionIntersectionType;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionUnionType;
use SimpleXMLElement;
use Throwable;

/**
 * The util for class
 */
class ClassUtil
{
    /**
     * get all fields' properties of class
     *
     * @param ReflectionClass $reflectionClass
     * @return ClassPropertyInfo[]
     * @throws PHPBeanException | ReflectionException
     */
    public static function getClassPropertiesInfo(ReflectionClass $reflectionClass): array
    {
        // todo 【效率优化】很适合修改成 yield. 以便节省内存，检查其他地方
        self::checkClass($reflectionClass);

        $reflectionProperties = $reflectionClass->getProperties();
        $defaultProperties = $reflectionClass->getDefaultProperties();
        // $reflectionClass->getReflectionConstants()

        /** @var ClassPropertyInfo[] $classPropertiesInfo */
        $classPropertiesInfo = [];
        foreach ($reflectionProperties as $reflectionProperty) {
            $classPropertiesInfo[] = self::getClassPropertyInfo($reflectionClass, $reflectionProperty);
        }

        foreach ($defaultProperties as $defaultPropertyName => $defaultPropertyValue) {
            // the $defaultPropertyName is from $reflectionClass->getDefaultProperties(), so ReflectionException will not be raised.
            $reflectionProperty = $reflectionClass->getProperty($defaultPropertyName);
            $classPropertiesInfo[] = self::getClassPropertyInfo($reflectionClass, $reflectionProperty);
        }

        return $classPropertiesInfo;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @param ReflectionProperty $reflectionProperty
     * @return ClassPropertyInfo
     * @throws PHPBeanException
     */
    private static function getClassPropertyInfo(ReflectionClass $reflectionClass, ReflectionProperty $reflectionProperty): ClassPropertyInfo
    {
        self::checkClassProperty($reflectionProperty, $reflectionClass->name);

        $getterMethod = null;
        $setterMethod = null;
        if (!empty($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC))) {
            $getterMethod = self::getGetterMethod($reflectionClass, $reflectionProperty);
            $setterMethod = self::getSetterMethod($reflectionClass, $reflectionProperty);
        }

        return new ClassPropertyInfo($reflectionClass->name, $reflectionProperty, $getterMethod, $setterMethod);
    }

    /**
     * Get one available setter method of one class property.
     *
     * @param ReflectionClass $reflectionClass
     * @param ReflectionProperty $reflectionProperty
     * @return ReflectionMethod|null
     */
    private static function getSetterMethod(ReflectionClass $reflectionClass, ReflectionProperty $reflectionProperty): ?ReflectionMethod
    {
        $optionSetterNameList = self::getOptionSetterNameList($reflectionProperty);
        foreach ($optionSetterNameList as $methodName) {
            $reflectionMethod = self::checkGetAvailableMethod($reflectionClass, $methodName, 1);
            if ($reflectionMethod != null) {
                return $reflectionMethod;
            }
        }
        return null;
    }

    /**
     *  Get Getter method of one class property. It will return null in the following cases:
     *  - not exists the setter method;
     *  - the setter method has any parameter.
     *
     * @param ReflectionClass $reflectionClass
     * @param ReflectionProperty $reflectionProperty
     * @return ReflectionMethod|null
     */
    private static function getGetterMethod(ReflectionClass $reflectionClass, ReflectionProperty $reflectionProperty): ?ReflectionMethod
    {
        $optionSetterNameList = self::getOptionGetterNameList($reflectionProperty);
        foreach ($optionSetterNameList as $methodName) {
            $reflectionMethod = self::checkGetAvailableMethod($reflectionClass, $methodName, 0);
            if ($reflectionMethod != null) {
                return $reflectionMethod;
            }
        }
        return null;
    }

    /**
     * @return string[]
     */
    private static function getOptionSetterNameList(ReflectionProperty $reflectionProperty): array
    {
        $methodNameList = [];
        $propertyName = $reflectionProperty->name;

        // 'camelCase' style
        $methodNameList[] = 'set' . ucfirst($propertyName);
        // 'snake_case' style
        $methodNameList[] = 'set' . '_' . $propertyName;

        // bool type
        if (TypeName::BOOL === $reflectionProperty->getType()->getName()) {
            if (str_starts_with($propertyName, 'is_')) {
                $methodNameList[] = 'set' . substr($propertyName, 4);
            } elseif (str_starts_with($propertyName, 'is')) {
                $methodNameList[] = 'set' . ucfirst(substr($propertyName, 3));
            }
        }

        return $methodNameList;
    }

    /**
     * @return string[]
     */
    private static function getOptionGetterNameList(ReflectionProperty $reflectionProperty): array
    {
        $methodNameList = [];
        $propertyName = $reflectionProperty->name;

        // 'camelCase' style
        $methodNameList[] = 'get' . ucfirst($propertyName);
        // 'snake_case' style
        $methodNameList[] = 'get' . '_' . $propertyName;

        // bool type
        if (TypeName::BOOL === $reflectionProperty->getType()->getName()) {
            if (str_starts_with($propertyName, 'is')) {
                $methodNameList[] = $propertyName;
            } else {
                $methodNameList[] = 'is' . ucfirst($propertyName);
            }
        }

        return $methodNameList;
    }

    /**
     * Check if the class has the method {$methodName}. It will return null in the following cases:
     * - not exists the setter method;
     * - the setter method is private;
     * - the setter method has more than one parameter;
     *
     * @param ReflectionClass $reflectionClass
     * @param string $methodName
     * @param int $parameterCount
     * @return ReflectionMethod|null
     */
    private static function checkGetAvailableMethod(ReflectionClass $reflectionClass, string $methodName, int $parameterCount): ?ReflectionMethod
    {
        if (!$reflectionClass->hasMethod($methodName)) {
            return null;
        }
        $reflectionMethod = $reflectionClass->getMethod($methodName);
        if (!$reflectionMethod->isPublic()) {
            return null;
        }
        if ($reflectionMethod->isStatic()) {
            return null;
        }
        if ($reflectionMethod->isAbstract()) {
            return null;
        }

        $numberOfParameters = $reflectionMethod->getNumberOfParameters();
        if ($numberOfParameters != $parameterCount) {
            return null;
        }
        return $reflectionMethod;
    }

    /**
     *  Get the class's reflection info. The {@see self::checkClass()} would check whether it is instantiable.
     *
     * @throws ReflectionException
     */
    public static function getReflectionClass(string $className): ReflectionClass
    {
        return new ReflectionClass($className);
    }

    /**
     * Create one instance. The {@see self::checkClass()} would check whether it is instantiable.
     *
     * @param string $className
     * @return object
     */
    public static function createNewInstance(string $className): object
    {
        // Create new instance with constructor is very complicated, because must get certain values from input data.
        return new ($className)();
    }

    /**
     * @param object|null $obj
     * @param ClassPropertyInfo $classPropertyInfo
     * @return mixed
     */
    public static function getFieldValue(?object $obj, ClassPropertyInfo $classPropertyInfo): mixed
    {
        // The "->" symbol can not get value if the class property is private.
        // This is goods enough to use for decoding a json/xml string.
        $targetValue = $obj->{$classPropertyInfo->propertyName} ?? ($obj->{$classPropertyInfo->getPropertyAlias()} ?? null);
        if ($targetValue === null) {
            return null;
        }

        if ($classPropertyInfo->isListType && ($obj instanceof SimpleXMLElement)) {
            $valueList = [];
            foreach ($targetValue as $value) {
                $valueList[] = $value;
            }
            return $valueList;
        }

        return $targetValue;
    }

    /**
     * Set the value of the class property.
     * This method will execute the handle ({@see ExtensionAfterHandle::afterHandle()}) before assign value.
     *
     * @param object $targetBeanInstance
     * @param ClassPropertyInfo $classPropertyInfo
     * @param mixed $value 简单类型的数据，如果非简单类型，则会做数据类型转换
     * @return void
     * @throws ReflectionException | PHPBeanException
     * @throws
     */
    public static function setPropertyValue(object $targetBeanInstance, ClassPropertyInfo $classPropertyInfo, mixed $value): void
    {
        $propertyName = $classPropertyInfo->propertyName;
        if ($value === null) {
            $value = $classPropertyInfo->reflectionProperty->getDefaultValue();
        }

        // after handle
        ClassUtil::executeAfterHandleAttribute($targetBeanInstance, $classPropertyInfo, $value);

        if ($value === null && !$classPropertyInfo->isNullAble) {
            $className = $classPropertyInfo->className;
            throw new PHPBeanException("Parse failure: cannot assign null to the property '{$className}::\${$propertyName}'. Is there missing a '?' character before the data type declaration?");
        }

        /** Use the setter method first. The function ({@see ClassUtil::getSetterMethod}) would check the method is available or not. */
        if ($classPropertyInfo->setterMethod !== null) {
            $classPropertyInfo->setterMethod->invoke($targetBeanInstance, $value);
            return;
        }

        $targetBeanInstance->{$propertyName} = $value;
    }

    /**
     * Check the class info, whether it can be used.
     *
     * @throws PHPBeanException
     */
    private static function checkClass(ReflectionClass $reflectionClass): void
    {
        if (!$reflectionClass->isInstantiable()) {
            throw new PHPBeanException("The class/enum/trait[{$reflectionClass->name}] can not be instantiated.");
        }
    }

    /**
     * Check the class property info, whether it can be used.
     *
     * @param ReflectionProperty $reflectionProperty
     * @param string $fullClassName
     * @return void
     * @throws PHPBeanException
     */
    private static function checkClassProperty(ReflectionProperty $reflectionProperty, string $fullClassName): void
    {
        if ($reflectionProperty->isReadOnly()) {
            throw new PHPBeanException("The property {$fullClassName}::\${$reflectionProperty->name} can not be readonly.");
        }
        if ($reflectionProperty->isStatic()) {
            // If supports, the value between different instance will be changed. It will be strange.
            throw new PHPBeanException("The property {$fullClassName}::\${$reflectionProperty->name} can not be static.");
        }

        $reflectionType = $reflectionProperty->getType();
        if (!($reflectionType instanceof ReflectionNamedType)) {
            $reflectionTypeName = match (true) {
                $reflectionType instanceof ReflectionUnionType => 'ReflectionUnionType',
                $reflectionType instanceof ReflectionIntersectionType => 'ReflectionIntersectionType',
                $reflectionType === null => TypeName::NULL,
                default => 'unknown',
            };
            throw new PHPBeanException("The property {$fullClassName}::\${$reflectionProperty->name}'s property type can only be type of ReflectionNamedType. The type is $reflectionTypeName now.");
        }

        $propertyType = $reflectionType->getName();
        if ($propertyType == TypeName::MIXED) {
            // If supports, then this functional library becomes meaningless.
            throw new PHPBeanException("The property {$fullClassName}::\${$reflectionProperty->name}'s property type is '{$propertyType}' which would never be supported.");
        }
    }

    /**
     * @throws PHPBeanException
     */
    public static function executeBeforeHandleAttribute(ReflectionClass $reflectionClass, ClassPropertyInfo $classPropertyInfo): void
    {
        $reflectionProperty = $classPropertyInfo->reflectionProperty;
        $reflectionAttributes = $reflectionProperty->getAttributes(ExtensionBeforeHandle::class, ReflectionAttribute::IS_INSTANCEOF);

        foreach ($reflectionAttributes as $reflectionAttribute) {
            try {
                /** @var ExtensionBeforeHandle $newInstance */
                $newInstance = $reflectionAttribute->newInstance();
            } catch (Error) {
                $attributeName = $reflectionAttribute->getName();
                throw new PHPBeanException("Execute BeforeHandle failure: can not instantiate the attribute class [$attributeName], the class of attribute must implement 'ExtensionBeforeHandle' interface.");
            }

            try {
                $newInstance->beforeHandle($reflectionClass, $classPropertyInfo);
            } catch (Throwable $e) {
                throw new PHPBeanException("Execute BeforeHandle failure: {$e->getMessage()}");
            }
        }
    }

    /**
     * @throws PHPBeanException
     */
    public static function executeAfterHandleAttribute(object $targetBeanInstance, ClassPropertyInfo $classPropertyInfo, mixed &$currentValue): void
    {
        $reflectionProperty = $classPropertyInfo->reflectionProperty;
        $reflectionAttributes = $reflectionProperty->getAttributes(ExtensionAfterHandle::class, ReflectionAttribute::IS_INSTANCEOF);
        foreach ($reflectionAttributes as $reflectionAttribute) {
            try {
                /** @var ExtensionAfterHandle $newInstance */
                $newInstance = $reflectionAttribute->newInstance();
            } catch (Error) {
                $attributeName = $reflectionAttribute->getName();
                throw new PHPBeanException("Execute AfterHandle failure: can not instantiate the attribute class [$attributeName], the attribute class must implement 'ExtensionAfterHandle' interface.");
            }

            try {
                $newInstance->afterHandle($targetBeanInstance, $classPropertyInfo, $currentValue);
            } catch (Throwable $e) {
                throw new PHPBeanException("Execute AfterHandle failure: {$e->getMessage()}");
            }
        }
    }
}

