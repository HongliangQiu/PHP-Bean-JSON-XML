<?php

namespace Attributes\Validator;

require(__DIR__ . "/../../autoload.php");

use Data\Bean\Validator\NotBlankTestBean;
use PHPBean\Exception\PHPBeanException;
use PHPBean\Exception\PHPBeanValidatorException;
use PHPBean\JSON;
use PHPUnit\Framework\TestCase;

class NotBlankTest extends TestCase
{
    /**
     * @throws PHPBeanException
     */
    public function test()
    {
        $this->expectException(PHPBeanValidatorException::class);
        $this->expectExceptionMessage("can not be blank");

        $jsonString = NotBlankTestBean::getJsonString();
        $parseObj = JSON::parseObj($jsonString, NotBlankTestBean::class);
    }
}
