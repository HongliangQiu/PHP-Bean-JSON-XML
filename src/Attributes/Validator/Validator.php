<?php

namespace PHPBean\Attributes\Validator;

use PHPBean\Attributes\ExtensionAfterHandle;

/**
 * https://www.cnblogs.com/Chenjiabing/p/13890384.html
 */
abstract class Validator implements ExtensionAfterHandle
{
    /**
     * Exception message when validate failure.
     *
     * @var string|null
     */
    protected ?string $exceptionMessage = null;
}