<?php
require __DIR__ . '/../vendor/autoload.php';
function eq($a, $b)
{
    if ($a !== $b) {
        print_r([$a, '!==', $b]);
        throw new \Exception('error');
    }
    print_r([$a, $b, 'true']);
}


$data = str_repeat('阿斯顿发到付eeee', 160);

$str_len = strlen($data);

// md5 签名
$sign = md5($data);

// 加密key必须为16位
$key = hex2bin(md5(1));
$sm4 = new \OneSm\Sm4($key);

// 加密
$d = $sm4->enDataEcb($data);
// 加密后的长度和原数据长度一致
eq(strlen($d), $str_len);

// 解密
$d = $sm4->deDataEcb($d);

// 解密后和原数据相等
eq(md5($d), $sign);


$iv = hex2bin(md5(2));
// 加密
$d = $sm4->enDataCbc($data, $iv);
// 加密后的长度和原数据长度一致
eq(strlen($d), $str_len);
// 解密
$d = $sm4->deDataCbc($d, $iv);
// 解密后和原数据相等
eq(md5($d), $sign);