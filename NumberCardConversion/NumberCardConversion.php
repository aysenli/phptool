<?php
/**
 * Created by PhpStorm.
 * User: aysen
 * Mail: 122195562@qq.com
 * Date: 2020/11/2
 * Time: 10:01
 */
class NumberCardConversion
{
    // 进制数
    private static $dnum = 36;
    // 前缀值
    private static $pre = 'sy';
    // 验长度
    private static $vc_len = 2;
    // 密码字典
    private static $cipherDic = [
        0  =>'0', 1  =>'1', 2  =>'2', 3  =>'3', 4  =>'4', 5  =>'5', 6  =>'6', 7  =>'7', 8  =>'8',
        9  =>'9', 10 =>'A', 11 =>'B', 12 =>'C', 13 =>'D', 14 =>'E', 15 =>'F', 16 =>'G', 17 =>'H',
        18 =>'I', 19 =>'J', 20 =>'K', 21 =>'L', 22 =>'M', 23 =>'N', 24 =>'O', 25 =>'P', 26 =>'Q',
        27 =>'R', 28 =>'S', 29 =>'T', 30 =>'U', 31 =>'V', 32 =>'W', 33 =>'X', 34 =>'Y', 35 =>'Z',
    ];

    /**
     * 数字转换
     * @param int $int 数字
     * @param int $format 格式长度
     * @return string
     */
    public static function encodeID($int, $format = 6) {
        // 初始化值
        $arr = [];
        // 处理状态
        $loop = true;
        // 数据处理
        while ($loop) {
            $arr[] = self::$cipherDic[bcmod($int, self::$dnum)];
            $int = bcdiv($int, self::$dnum, 0);
            if ($int == '0') {
                $loop = false;
            }
        }

        // 长度补位
        if (count($arr) < $format)
        {
            $arr = array_pad($arr, $format, self::$cipherDic[0]);
        }

        // 数据转换
        return implode('', array_reverse($arr));
    }

    /**
     * 卡号转换
     * @param string $card 卡号
     * @return int
     */
    public static function decodeID($card) {
        // 反转数组
        $dedic = array_flip(self::$cipherDic);
        // 去掉补值
        $id = ltrim($card, $dedic[0]);
        // 反转字符
        $id = strrev($id);
        // 初始化值
        $v = 0;
        // 遍历数据
        for ($i = 0, $j = strlen($id); $i < $j; $i++) {
            $v = bcadd(bcmul($dedic[$id {$i}], bcpow(self::$dnum, $i, 0), 0), $v, 0);
        }

        // 数据返回
        return $v;
    }

    /**
     * 数字2卡号
     * @param int $int 数字
     * @param int $format 格式长度
     * @return string
     */
    public static function generateCardByNum($int, $format = 6)
    {
        // 数字转换
        $card = self::encodeID($int, $format);
        // 校验生成
        $card_vc = substr(md5(self::$pre . $card), 0, self::$vc_len);
        // 数据拼接
        return strtolower(self::$pre . $card . $card_vc);
    }

    /**
     * 卡号2数字
     * @param string $card 卡号
     * @return int
     */
    public static function generateNumByCard($card)
    {
        // 前缀长度
        $pre_len = strlen(self::$pre);
        // 卡号长度
        $card_len = strlen($card) - $pre_len - self::$vc_len;
        // 数据截取
        $card = substr($card, $pre_len, $card_len);
        // 转换数据
        return self::decodeID(strtoupper($card));
    }
}