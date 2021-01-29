## php国密SM3签名算法

### 安装

```shell 
composer require lizhichao/one-sm
``` 

### 使用
```php
<?php
require __DIR__ . '/vendor/autoload.php';

$sm3 = new \OneSm\Sm3();

// 字符串签名
echo $sm3->sign('abc') . PHP_EOL;
echo $sm3->sign(str_repeat("adfas哈哈哈", 100)) . PHP_EOL;


// 文件签名
echo $sm3->signFile(__FILE__) . PHP_EOL;
```
### 性能测试
和 [openssl](https://github.com/openssl/openssl) , [SM3-PHP](https://github.com/DongyunLee/SM3-PHP) 性能测试

```shell
php bench.php
```
结果
```
openssl:4901d7181a1024b8c0f59b8d3c5c6d96b4b707ad10e8ebc8ece5dc49364a3067
one-sm3:4901d7181a1024b8c0f59b8d3c5c6d96b4b707ad10e8ebc8ece5dc49364a3067
SM3-PHP:4901d7181a1024b8c0f59b8d3c5c6d96b4b707ad10e8ebc8ece5dc49364a3067
openssl time:6.3741207122803ms
one-sm3 time:8.1770420074463ms
SM3-PHP time:1738.5928630829ms

```
[测试代码bench.php](https://github.com/lizhichao/sm/blob/master/bench.php)