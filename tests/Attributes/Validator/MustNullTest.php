<?php

namespace Attributes\Validator;

require(__DIR__ . "/../../autoload.php");

use Data\Bean\Validator\MustNullTestBean;
use PHPBean\Exception\PHPBeanException;
use PHPBean\Exception\PHPBeanValidatorException;
use PHPBean\JSON;
use PHPUnit\Framework\TestCase;

class MustNullTest extends TestCase
{
    /**
     * @throws PHPBeanException
     */
    public function test()
    {
        $this->expectException(PHPBeanValidatorException::class);
        $this->expectExceptionMessage("can only be null");

        $jsonString = MustNullTestBean::getJsonString();
        $parseObj = JSON::parseObj($jsonString, MustNullTestBean::class);
    }
}
