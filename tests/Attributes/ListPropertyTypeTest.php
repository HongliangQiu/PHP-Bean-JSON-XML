<?php

namespace Attributes;

require(__DIR__ . "/../autoload.php");

use Data\ListPropertyTypeBean;
use PHPBean\Exception\PHPBeanException;
use PHPBean\JSON;
use PHPUnit\Framework\TestCase;

class ListPropertyTypeTest extends TestCase
{
    /**
     * @throws PHPBeanException
     */
    public function test()
    {
        $listPropertyTypeBean = ListPropertyTypeBean::getInstance();
        $jsonString = ListPropertyTypeBean::getJsonString();
        $parseObj = JSON::parseObj($jsonString, ListPropertyTypeBean::class);
        self::assertEquals($parseObj, $listPropertyTypeBean);
    }
}
