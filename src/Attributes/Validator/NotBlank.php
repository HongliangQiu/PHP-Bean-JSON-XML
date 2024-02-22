<?php

namespace PHPBean\Attributes\Validator;

use Attribute;
use PHPBean\Exception\PHPBeanValidatorException;
use PHPBean\Utils\ClassPropertyInfo;

/**
 * Value can only not be blank.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class NotBlank extends Validator
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
        if (is_numeric($currentValue)) {
            return;
        }
        if (is_scalar($currentValue)) {
            if (strlen(trim(strval($currentValue))) > 0) {
                return;
            }
        }

        throw new PHPBeanValidatorException($this->exceptionMessage
            ?? "Validate failure: Property '{$classPropertyInfo->className}::\${$classPropertyInfo->propertyName}' value type should be string and can not be blank.");
    }
}