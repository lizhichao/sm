<?php
require __DIR__ . '/vendor/autoload.php';

// 字符串签名
echo \OneSm\Sm3::sign('abc') . PHP_EOL;
echo \OneSm\Sm3::sign(str_repeat("adfas哈哈哈", 100)) . PHP_EOL;


// 文件签名
echo \OneSm\Sm3::signFile(__FILE__) . PHP_EOL;
