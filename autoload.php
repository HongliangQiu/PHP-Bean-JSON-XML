<?php

// 优先使用 composer 的自动加载
require_once(__DIR__ . "/vendor/autoload.php");

/**
 * 注册自动加载，主要便于开发环境使用
 *
 * @param string $class 完全标准的类名。
 * @return void
 */
spl_autoload_register(function ($class) {
    // 具体项目的命名空间前缀，和 composer.json 里配置的一致。
    $prefix = "DataMonitor\\";

    // 命名空间前缀对应的基础目录，一般是 “__DIR__ . '/src/'”，但是当前项目没有 src，因此为“/”
    $base_dir = __DIR__ . '/';

    // 该类使用了此命名空间前缀
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // 否，交给下一个已注册的自动加载函数
        return;
    }

    // 获取相对类名
    $relative_class = substr($class, $len);

    // 命名空间前缀替换为基础目录，将相对类名中命名空间分隔符替换为目录分隔符，附加 .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // 如果文件存在，加载它
    if (file_exists($file)) {
        require $file;
    }
});