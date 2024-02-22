<?php

namespace PHPBean\Attributes\Validator;

use Attribute;
use PHPBean\Exception\PHPBeanValidatorException;
use PHPBean\Utils\ClassPropertyInfo;

/**
 * Value can not be null.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class MustNotNull extends Validator
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
        if (null !== $currentValue) {
            return;
        }

        throw new PHPBeanValidatorException($this->exceptionMessage
            ?? "Validate failure: Property '{$classPropertyInfo->className}::\${$classPropertyInfo->propertyName}' value can not be null.");
    }
}