<?php

namespace Data;

interface BeanInstance
{
    public static function getInstance() : object;

    public static function getJsonString() : string;
}