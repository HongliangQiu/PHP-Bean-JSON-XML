<?php

namespace Attributes\Validator;

require(__DIR__ . "/../../autoload.php");

use Data\Bean\Validator\MustNotNullTestBean;
use PHPBean\Exception\PHPBeanException;
use PHPBean\Exception\PHPBeanValidatorException;
use PHPBean\JSON;
use PHPUnit\Framework\TestCase;

class MustNotNullTest extends TestCase
{
    /**
     * @throws PHPBeanException
     */
    public function test()
    {
        $this->expectException(PHPBeanValidatorException::class);
        $this->expectExceptionMessage("can not be null");

        $jsonString = MustNotNullTestBean::getJsonString();
        $parseObj = JSON::parseObj($jsonString, MustNotNullTestBean::class);
    }
}
