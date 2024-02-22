<?php

namespace PHPBean\Attributes\Validator;

use Attribute;
use PHPBean\Exception\PHPBeanValidatorException;
use PHPBean\Utils\ClassPropertyInfo;

/**
 * string/array value length can only smaller or equal than $maxLength
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Length extends Validator
{
    private int $maxLength;

    public function __construct(int $maxLength, string $exceptionMessage = null)
    {
        $this->maxLength = $maxLength;
        $this->exceptionMessage = $exceptionMessage;
    }

    public function afterHandle(object $targetBeanInstance, ClassPropertyInfo $classPropertyInfo, mixed &$currentValue): void
    {
        if (is_string($currentValue)) {
            if (mb_strlen($currentValue, 'UTF-8') > $this->maxLength) {
                throw new PHPBeanValidatorException($this->exceptionMessage
                    ?? "Validate failure: Property '{$classPropertyInfo->className}::\${$classPropertyInfo->propertyName}' string value length is bigger than $this->maxLength.");
            }
        } elseif (is_array($currentValue)) {
            if (count($currentValue) > $this->maxLength) {
                throw new PHPBeanValidatorException($this->exceptionMessage
                    ?? "Validate failure: Property '{$classPropertyInfo->className}::\${$classPropertyInfo->propertyName}' array value length/count is bigger than $this->maxLength.");
            }
        } else {
            throw new PHPBeanValidatorException($this->exceptionMessage
                ?? "Validate failure: Can not use '#[Length]' on property '{$classPropertyInfo->className}::\${$classPropertyInfo->propertyName}', it's type is not be supported");
        }
    }
}