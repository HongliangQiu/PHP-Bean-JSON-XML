<?php

namespace PHPBeanTest\Data\Bean;

use PHPBean\Attributes\PropertyAlias;
use stdClass;

class GetterSetterBean
{
    public bool $isCod;
    public bool $gift;

    public bool $is_snake_case;

    public function isCod(): bool
    {
        return $this->isCod;
    }

    public function isGift(): bool
    {
        return $this->gift;
    }

    public function isIsSnakeCase(): bool
    {
        return $this->is_snake_case;
    }





}