<?php

namespace PHPBean\Exception;

interface PHPBeanExceptionHandler
{
    public function execute(PHPBeanException $Exception): void;
}