<?php

namespace OneSm;

class Sm3
{
    const IV      = '7380166f4914b2b9172442d7da8a0600a96f30bc163138aae38dee4db0fb0e4e';
    const LEN     = 512;
    const STR_LEN = 64;

    public static function sign($str)
    {
        $l   = strlen($str) * 8;
        $k   = self::getK($l);
        $bt  = self::getB($k);
        $str = $str . $bt . pack('J', $l);

        $count = strlen($str);
        $l     = $count / self::STR_LEN;
        $vr    = hex2bin(self::IV);
        for ($i = 0; $i < $l; $i++) {
            $vr = self::cf($vr, substr($str, $i * self::STR_LEN, self::STR_LEN));
        }
        return bin2hex($vr);

    }

    private static function getK($l)
    {
        $v = $l % self::LEN;
        return $v + self::STR_LEN < self::LEN
            ? self::LEN - self::STR_LEN - $v - 1
            : (self::LEN * 2) - self::STR_LEN - $v - 1;
    }

    private static function getB($k)
    {
        $arg = [128];
        $arg = array_merge($arg, array_fill(0, intval($k / 8), 0));
        return pack('C*', ...$arg);
    }

    public static function signFile($file)
    {
        $l  = filesize($file) * 8;
        $k  = self::getK($l);
        $bt = self::getB($k) . pack('J', $l);

        $hd  = fopen($file, 'r');
        $vr  = hex2bin(self::IV);
        $str = fread($hd, self::STR_LEN);
        if ($l > self::LEN - self::STR_LEN - 1) {
            do {
                $vr  = self::cf($vr, $str);
                $str = fread($hd, self::STR_LEN);
            } while (!feof($hd));
        }

        $str   = $str . $bt;
        $count = strlen($str) * 8;
        $l     = $count / self::LEN;
        for ($i = 0; $i < $l; $i++) {
            $vr = self::cf($vr, substr($str, $i * self::STR_LEN, self::STR_LEN));
        }
        return bin2hex($vr);
    }


    private static function t($i)
    {
        return $i < 16 ? 0x79cc4519 : 0x7a879d8a;
    }

    private static function cf($ai, $bi)
    {
        $wr = array_values(unpack('N*', $bi));
        for ($i = 16; $i < 68; $i++) {
            $wr[$i] = self::p1($wr[$i - 16]
                    ^
                    $wr[$i - 9]
                    ^
                    self::lm($wr[$i - 3], 15))
                ^
                self::lm($wr[$i - 13], 7)
                ^
                $wr[$i - 6];
        }
        $wr1 = [];
        for ($i = 0; $i < 64; $i++) {
            $wr1[] = $wr[$i] ^ $wr[$i + 4];
        }

        list($a, $b, $c, $d, $e, $f, $g, $h) = array_values(unpack('N*', $ai));

        for ($i = 0; $i < 64; $i++) {
            $ss1 = self::lm(
                (self::lm($a, 12) + $e + self::lm(self::t($i), $i % 32) & 0xffffffff),
                7);
            $ss2 = $ss1 ^ self::lm($a, 12);
            $tt1 = (self::ff($i, $a, $b, $c) + $d + $ss2 + $wr1[$i]) & 0xffffffff;
            $tt2 = (self::gg($i, $e, $f, $g) + $h + $ss1 + $wr[$i]) & 0xffffffff;
            $d   = $c;
            $c   = self::lm($b, 9);
            $b   = $a;
            $a   = $tt1;
            $h   = $g;
            $g   = self::lm($f, 19);
            $f   = $e;
            $e   = self::p0($tt2);
        }

        return pack('N*', $a, $b, $c, $d, $e, $f, $g, $h) ^ $ai;
    }


    private static function ff($j, $x, $y, $z)
    {
        return $j < 16 ? $x ^ $y ^ $z : ($x & $y) | ($x & $z) | ($y & $z);
    }

    private static function gg($j, $x, $y, $z)
    {
        return $j < 16 ? $x ^ $y ^ $z : ($x & $y) | (~$x & $z);
    }


    private static function lm($a, $n)
    {
        return ($a >> (32 - $n) | (($a << $n) & 0xffffffff));
    }

    private static function p0($x)
    {
        return $x ^ self::lm($x, 9) ^ self::lm($x, 17);
    }

    private static function p1($x)
    {
        return $x ^ self::lm($x, 15) ^ self::lm($x, 23);
    }

}