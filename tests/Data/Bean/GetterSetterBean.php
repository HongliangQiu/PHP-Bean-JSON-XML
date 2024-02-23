<?php

namespace Data\Bean;

use Data\BeanInstance;
use PHPBean\Attributes\ListPropertyType;
use PHPBean\Enum\TypeName;

class GetterSetterBean implements BeanInstance
{
    #[ListPropertyType(TypeName::STRING)]
    private array $stringList = ["string"];
    private string $specNo = "specNo";
    private bool $isCod = true;
    private bool $gift = false;

    public static function getInstance(): GetterSetterBean
    {
        return new GetterSetterBean();
    }

    public static function getJsonString(): string
    {
        $setterBean = self::getInstance();
        $data = array(
            'stringList' => $setterBean->getStringList(),
            'specNo'     => $setterBean->getSpecNo(),
            'isCod'      => $setterBean->isCod(),
            'gift'       => $setterBean->isGift(),
        );

        return json_encode($data);
    }

    public function getStringList(): array
    {
        return $this->stringList;
    }

    public function setStringList(array $stringList): void
    {
        $this->stringList = $stringList;
    }

    public function getSpecNo(): string
    {
        return $this->specNo;
    }

    public function setSpecNo(string $specNo): void
    {
        $this->specNo = $specNo;
    }

    public function isCod(): bool
    {
        return $this->isCod;
    }

    public function setIsCod(bool $isCod): void
    {
        $this->isCod = $isCod;
    }

    public function isGift(): bool
    {
        return $this->gift;
    }

    public function setGift(bool $gift): void
    {
        $this->gift = $gift;
    }
}