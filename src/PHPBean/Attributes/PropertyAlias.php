<?php

namespace PHPBean\Attributes;

use Attribute;
use PHPBean\Utils\ClassPropertyInfo;
use ReflectionClass;

/**
 * The extension of class property alias
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class PropertyAlias implements ExtensionBeforeHandle
{
    /** @var string $alias alias of one class property */
    private string $alias;

    /**
     * @param string $alias
     */
    public function __construct(string $alias)
    {
        assert($alias != '', 'PropertyAlias::__construct failure: alias can not be empty.');
        $this->alias = $alias;
    }

    public function beforeHandle(ReflectionClass $reflectionClass, ClassPropertyInfo $classPropertyInfo): void
    {
        $classPropertyInfo->setPropertyAlias($this->alias);
    }
}