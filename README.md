## php国密SM3签名算法

```php

// 字符串签名
echo \OneSm\Sm3::sign('abc') . PHP_EOL;
echo \OneSm\Sm3::sign(str_repeat("adfas哈哈哈", 100)) . PHP_EOL;


// 文件签名
echo \OneSm\Sm3::sign_file(__FILE__) . PHP_EOL;
```
