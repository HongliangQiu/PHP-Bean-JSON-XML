<?php

namespace Attributes\Validator;

require(__DIR__ . "/../../autoload.php");

use Data\Bean\Validator\LengthTestBean;
use PHPBean\Exception\PHPBeanException;
use PHPBean\Exception\PHPBeanValidatorException;
use PHPBean\JSON;
use PHPUnit\Framework\TestCase;

class LengthTest extends TestCase
{
    /**
     * @throws PHPBeanException
     */
    public function testStringLength()
    {
        $this->expectException(PHPBeanValidatorException::class);
        $this->expectExceptionMessage("string value max length");

        $jsonString = LengthTestBean::getJsonString("1234567");
        $parseObj = JSON::parseObj($jsonString, LengthTestBean::class);
    }

    /**
     * @throws PHPBeanException
     */
    public function testArrayLength()
    {
        $this->expectException(PHPBeanValidatorException::class);
        $this->expectExceptionMessage("array value max length");

        $jsonString = LengthTestBean::getJsonString("1", [1, 2, 3, 4]);
        $parseObj = JSON::parseObj($jsonString, LengthTestBean::class);
    }
}
