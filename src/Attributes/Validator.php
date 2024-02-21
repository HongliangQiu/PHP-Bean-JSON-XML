<?php

namespace PHPBean\Attributes;

use Attribute;
use PHPBean\Utils\ClassPropertyInfo;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Validator implements ExtensionAfterHandle
{

    public function afterHandle(object $targetBeanInstance, ClassPropertyInfo $classPropertyInfo, mixed &$currentValue): void
    {
        // https://www.cnblogs.com/Chenjiabing/p/13890384.html  参考这个，可以不断扩充类
        // todo 【新功能】
    }
}