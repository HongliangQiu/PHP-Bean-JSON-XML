<?php

namespace Attributes\Validator;

require(__DIR__ . "/../../autoload.php");

use Data\Bean\Validator\PatternTestBean;
use PHPBean\Exception\PHPBeanException;
use PHPBean\Exception\PHPBeanValidatorException;
use PHPBean\JSON;
use PHPUnit\Framework\TestCase;

class PatternTest extends TestCase
{
    /**
     * @throws PHPBeanException
     */
    public function test()
    {
        $this->expectException(PHPBeanValidatorException::class);
        $this->expectExceptionMessage("pattern not match");

        $jsonString = PatternTestBean::getJsonString();
        $parseObj = JSON::parseObj($jsonString, PatternTestBean::class);
    }
}