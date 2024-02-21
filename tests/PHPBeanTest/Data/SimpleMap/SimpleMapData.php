<?php

namespace PHPBeanTest\Data\SimpleMap;

class SimpleMapData
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

    public static function getJsonString(): bool|string
    {
        $simpleMapData = new SimpleMapData();
        return json_encode($simpleMapData, JSON_UNESCAPED_SLASHES);
    }
}