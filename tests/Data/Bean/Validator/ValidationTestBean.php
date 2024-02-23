<?php

namespace Data\Bean\Validator;

use Data\BeanInstance;
use PHPBean\Attributes\Validator\AssertFalse;
use PHPBean\Attributes\Validator\AssertTrue;
use PHPBean\Attributes\Validator\Future;
use PHPBean\Attributes\Validator\Length;
use PHPBean\Attributes\Validator\MustNotNull;
use PHPBean\Attributes\Validator\MustNull;
use PHPBean\Attributes\Validator\NotBlank;
use PHPBean\Attributes\Validator\Past;
use PHPBean\Attributes\Validator\Pattern;

class ValidationTestBean implements BeanInstance
{
    #[AssertFalse]
    public bool $assertFalse = false;
    #[AssertTrue]
    public bool $assertTrue = true;
    #[Future]
    public string $future = "9999-02-23 15:10:23";
    #[Length(10)]
    public string $length = "123456789";
    #[MustNotNull]
    public string $mustNotNull = "111";
    #[MustNull]
    public ?string $mustNull = null;
    #[NotBlank]
    public string $notBlank = "12345";
    #[Past]
    public string $past = "2000-02-23 15:11:20";
    #[Pattern("/\d{11}/")]
    public string $pattern = "01234567891";

    public static function getInstance(): object
    {
        return new ValidationTestBean();
    }

    public static function getJsonString(): string
    {
        return json_encode(self::getInstance());
    }
}
