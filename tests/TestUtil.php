<?php

class TestUtil
{
    public static function makeStdClass(array $arrayMap): stdClass
    {
        $stdClass = new stdClass();
        foreach ($arrayMap as $key => $value) {
            $stdClass->{$key} = $value;
        }
        return $stdClass;
    }
}