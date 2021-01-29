## php国密SM3签名算法

### 安装

```shell 
composer require lizhichao/one-sm
``` 

### 使用
```php
require __DIR__ . '/vendor/autoload.php';

// 字符串签名
echo \OneSm\Sm3::sign('abc') . PHP_EOL;
echo \OneSm\Sm3::sign(str_repeat("adfas哈哈哈", 100)) . PHP_EOL;


// 文件签名
echo \OneSm\Sm3::signFile(__FILE__) . PHP_EOL;
```
### 性能测试
和 [openssl](https://github.com/openssl/openssl) , [SM3-PHP](https://github.com/DongyunLee/SM3-PHP) 性能测试

```shell
php bench.php
```
结果
```
openssl:c4cae8d8730206d130e1eef9de3e00225da0b556cfcb8d0076561352ff19f769
one-sm3:c4cae8d8730206d130e1eef9de3e00225da0b556cfcb8d0076561352ff19f769
SM3-PHP:c4cae8d8730206d130e1eef9de3e00225da0b556cfcb8d0076561352ff19f769
openssl time:4.8391819000244ms
one-sm3 time:5.7239532470703ms
SM3-PHP time:684.2360496521ms

```
[测试代码bench.php](https://github.com/lizhichao/sm/blob/master/bench.php)