<?php

namespace PHPBean\Utils;

use PHPBean\Attributes\ListPropertyType;
use PHPBean\Enum\TypeName;
use ReflectionMethod;
use ReflectionProperty;

/**
 * class's property info.
 */
class ClassPropertyInfo
{
    /**
     * @var string The class name of current property.
     */
    public readonly string $className;
    /**
     * @var string The property name.
     */
    public readonly string $propertyName;
    /**
     * @var string The alias name of property. It's default value is property name.
     */
    private string $propertyAlias;
    /**
     * @var string Type of the property, the name could be a class name.
     * @see ReflectionNamedType::getName()
     */
    private string $propertyType;
    /**
     * @var bool Whether the property type is the list.
     */
    public readonly bool $isListType;
    /**
     * @var int The array list dimension, always used along with "$isListType == true.
     *
     * @see ListPropertyType::$listDimension
     */
    private int $listDimension = 0;
    /**
     * @var bool whether the class property support null
     * @see ReflectionProperty::getType() get the property type info of ReflectionType.
     * @see ReflectionType::allowsNull()
     */
    public readonly bool $isNullAble;
    /**
     * @var ReflectionMethod|null
     * @see ClassUtil::getSetterMethod()
     */
    public readonly ?ReflectionMethod $setterMethod;

    /**
     * @var ReflectionMethod|null
     * @see ClassUtil::getGetterMethod()
     */
    public readonly ?ReflectionMethod $getterMethod;
    /**
     * the property's ReflectionProperty info
     *
     * @var ReflectionProperty
     */
    public readonly ReflectionProperty $reflectionProperty;

    /**
     * @param string $className
     * @param ReflectionProperty $reflectionProperty
     * @param ReflectionMethod|null $getterMethod
     * @param ReflectionMethod|null $setterMethod
     */
    public function __construct(string $className, ReflectionProperty $reflectionProperty, ?ReflectionMethod $getterMethod, ?ReflectionMethod $setterMethod)
    {
        $this->className = $className;
        $this->propertyName = $reflectionProperty->name;
        $this->propertyType = $reflectionProperty->getType()->getName();
        $this->reflectionProperty = $reflectionProperty;

        $this->getterMethod = $getterMethod;
        $this->setterMethod = $setterMethod;

        // The alias == property name by default.
        $this->propertyAlias = $this->propertyName;
        $this->isNullAble = $reflectionProperty->getType()->allowsNull();
        $this->isListType = $this->propertyType == TypeName::ARRAY;
    }

    /**
     * @return string
     */
    public function getPropertyAlias(): string
    {
        return $this->propertyAlias;
    }

    /**
     * @param string $propertyAlias
     */
    public function setPropertyAlias(string $propertyAlias): void
    {
        $this->propertyAlias = $propertyAlias;
    }

    /**
     * @return string
     */
    public function getPropertyType(): string
    {
        return $this->propertyType;
    }

    /**
     * @param string $propertyType
     */
    public function setPropertyType(string $propertyType): void
    {

        $this->propertyType = $propertyType;
    }

    /**
     * @return int
     */
    public function getListDimension(): int
    {
        return $this->listDimension;
    }

    /**
     * @param int $listDimension
     */
    public function setListDimension(int $listDimension): void
    {
        $this->listDimension = $listDimension;
    }
}