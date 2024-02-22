<?php

namespace PHPBean\Attributes\Validator;

use Attribute;
use PHPBean\Exception\PHPBeanValidatorException;
use PHPBean\Utils\ClassPropertyInfo;

/**
 * string value should match the regexp
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Pattern extends Validator
{
    private string $regexp;

    public function __construct(string $regexp, string $exceptionMessage = null)
    {
        $this->regexp = $regexp;
        $this->exceptionMessage = $exceptionMessage;
    }

    public function afterHandle(object $targetBeanInstance, ClassPropertyInfo $classPropertyInfo, mixed &$currentValue): void
    {
        if (!is_string($currentValue)) {
            return;
        }

        if (preg_match($this->regexp, $currentValue)) {
            return;
        }

        throw new PHPBeanValidatorException($this->exceptionMessage
            ?? "Validate failure: Property '{$classPropertyInfo->className}::\${$classPropertyInfo->propertyName}' value does not match the regexp.");
    }
}