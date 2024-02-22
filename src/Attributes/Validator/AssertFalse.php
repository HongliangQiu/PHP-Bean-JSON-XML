<?php

namespace PHPBean\Attributes\Validator;

use Attribute;
use PHPBean\Exception\PHPBeanValidatorException;
use PHPBean\Utils\ClassPropertyInfo;

/**
 * Value can only be false.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class AssertFalse extends Validator
{
    /**
     * @param string|null $exceptionMessage
     */
    public function __construct(string $exceptionMessage = null)
    {
        $this->exceptionMessage = $exceptionMessage;
    }

    public function afterHandle(object $targetBeanInstance, ClassPropertyInfo $classPropertyInfo, mixed &$currentValue): void
    {
        if (false === $currentValue) {
            return;
        }

        throw new PHPBeanValidatorException($this->exceptionMessage
            ?? "Validate failure: Property '{$classPropertyInfo->className}::\${$classPropertyInfo->propertyName}' value can only be false.");
    }
}