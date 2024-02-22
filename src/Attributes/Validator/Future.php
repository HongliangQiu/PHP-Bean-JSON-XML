<?php

namespace PHPBean\Attributes\Validator;

use Attribute;
use PHPBean\Exception\PHPBeanValidatorException;
use PHPBean\Utils\ClassPropertyInfo;

/**
 * Not blank string value should be a future datetime. Ignore blank string value or null value.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Future extends Validator
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
        if (null === $currentValue) {
            return;
        }
        if (!is_string($currentValue)) {
            return;
        }

        if (strtotime($currentValue) <= time()) {
            throw new PHPBeanValidatorException($this->exceptionMessage
                ?? "Validate failure: Property '{$classPropertyInfo->className}::\${$classPropertyInfo->propertyName}' value type should be a future datetime.");
        }
    }
}