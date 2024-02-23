<?php

date_default_timezone_set('PRC');

spl_autoload_register(function ($className) {
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    $file = __DIR__ . DIRECTORY_SEPARATOR . $className . '.php';
    if (is_file($file)) {
        require $file;
    }
});