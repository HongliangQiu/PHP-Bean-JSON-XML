<?php

namespace PHPBean\Attributes;

use Attribute;
use PHPBean\Exception\PHPBeanException;
use PHPBean\Utils\ClassPropertyInfo;
use ReflectionClass;

/**
 * The extension of class property.
 * This is for pointing out the data type of list (array).
 * As of PHP8, we can not declare the data type of array by a type declaration.
 * We can get an array type "array<string>" by adding the attribute "#[ListPropertyType("string")]" on the array type property.
 * <p>
 * The example is following:
 * <code>
 * #[ListPropertyType("string")]
 * public array $snList;
 * </code>
 * </p>
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class ListPropertyType implements ExtensionBeforeHandle
{
    /** @var string Type name of list, for example 'string', means List<string> (string[]). */
    private string $typeName;
    /** @var int List dimension, if $typeName = 'string' and $listDimension = 2, means string[][]. */
    private int $listDimension;

    /**
     * @param string $typeName
     * @param int $listDimension
     */
    public function __construct(string $typeName, int $listDimension = 1)
    {
        assert(!empty($typeName));
        assert($listDimension >= 1);
        $this->typeName = $typeName;
        $this->listDimension = $listDimension;
    }

    public function beforeHandle(ReflectionClass $reflectionClass, ClassPropertyInfo $classPropertyInfo): void
    {
        if (!$classPropertyInfo->isListType) {
            $className = $reflectionClass->name;
            $propertyName = $classPropertyInfo->propertyName;
            throw new PHPBeanException("Can not use the attribute[ListPropertyType] on property[{$className}::\${$propertyName}] which type is not array.");
        }

        $classPropertyInfo->setListDimension($this->listDimension);
        $classPropertyInfo->setPropertyType($this->typeName);
    }

}