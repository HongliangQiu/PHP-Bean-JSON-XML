<?php

namespace PHPBeanTest\Data\SimpleMap;

use stdClass;

class SimpleMapBean
{
    public string|null $vNull = null;
    public string $vString = 'string';
    public bool $vBool = false;
    public bool $vTrue = true;
    public bool $vFalse = false;
    public bool $vBoolean = true;
    public int $vInt = 10;
    public int $vInteger = -1;
    public float $vFloat = 1.234;
    public float $vDouble = 1.3456789;
    public array $vArray = [];
    // public ?object $vObject = null;
    public ?stdClass $vStdClass = null;

    public static function getInstance()
    {
        $simpleMapData = new SimpleMapBean();

        $stdClass = new stdClass();
        $stdClass->m1 = "m1";
        $stdClass->m2 = "m2";
        $simpleMapData->vStdClass = $stdClass;

        // $simpleMapData->vObject = (object)["a" => "a", "b" => "b"];

        return $simpleMapData;
    }

    public static function getJsonString(): bool|string
    {
        return json_encode(self::getInstance(), JSON_UNESCAPED_SLASHES);
    }
}