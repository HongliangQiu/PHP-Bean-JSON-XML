<?php

namespace Attributes\Validator;

require(__DIR__ . "/../../autoload.php");

use Data\Bean\Validator\PastTestBean;
use PHPBean\Exception\PHPBeanException;
use PHPBean\Exception\PHPBeanValidatorException;
use PHPBean\JSON;
use PHPUnit\Framework\TestCase;

class PastTest extends TestCase
{

    /**
     * @throws PHPBeanException
     */
    public function test()
    {
        $this->expectException(PHPBeanValidatorException::class);
        $this->expectExceptionMessage("past date time expect");

        $jsonString = PastTestBean::getJsonString("9999-02-23 14:33:52");
        $parseObj = JSON::parseObj($jsonString, PastTestBean::class);
    }
}
