<?php
require __DIR__ . '/vendor/autoload.php';

$sm3 = new \OneSm\Sm3();

// 字符串签名
echo $sm3->sign('abc') . PHP_EOL;
echo $sm3->sign(str_repeat("adfas哈哈哈", 100)) . PHP_EOL;


// 文件签名
echo $sm3->signFile(__FILE__) . PHP_EOL;
