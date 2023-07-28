<?php

namespace PHPBean\Attributes;

use PHPBean\Utils\ClassPropertyInfo;
use PHPBean\Utils\ClassUtil;
use Throwable;

// todo 【效率优化】全部做成缓存，先后处理
interface ExtensionAfterHandle
{
    /**
     * The after handle which before set the class property value. Maybe you can modify the class property value {$currentValue} by this handle.
     *
     * @param object $targetBeanInstance Current class
     * @param ClassPropertyInfo $classPropertyInfo Current property info which was build by {@see ClassUtil::getClassPropertyInfo()} method
     * @param mixed $currentValue Current value. Maybe you can modify it by this handle.
     * @return void
     * @throws Throwable
     */
    public function afterHandle(object $targetBeanInstance, ClassPropertyInfo $classPropertyInfo, mixed &$currentValue): void;
}