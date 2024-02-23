<?php

namespace Attributes\Validator;

require(__DIR__ . "/../../autoload.php");

use Data\Bean\Validator\AssertFalseTestBean;
use PHPBean\Exception\PHPBeanException;
use PHPBean\Exception\PHPBeanValidatorException;
use PHPBean\JSON;
use PHPUnit\Framework\TestCase;

class AssertFalseTest extends TestCase
{
    /**
     * @throws PHPBeanException
     */
    public function test()
    {
        $this->expectException(PHPBeanValidatorException::class);
        $this->expectExceptionMessage("false expect");

        $jsonString = AssertFalseTestBean::getJsonString(true);
        JSON::parseObj($jsonString, AssertFalseTestBean::class);
    }
}
