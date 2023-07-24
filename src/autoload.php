<?php

namespace PHPBean;

spl_autoload_register(function ($className) {
    static $prefix = __NAMESPACE__ . DIRECTORY_SEPARATOR;
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    $file = __DIR__ . DIRECTORY_SEPARATOR . $className . '.php';

    if (!str_starts_with($className, $prefix)) {
        return;
    }
    if (is_file($file)) {
        require $file;
    }
});

