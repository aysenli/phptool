<?php
/**
 * Created by PhpStorm.
 * User: aysen
 * Mail: 122195562@qq.com
 * Date: 2020/11/2
 * Time: 10:01
 */ 
include_once 'NumberCardConversion.php';
$int = 2584513;

echo '案例数值: ' . $int . "\r\n";
$card = NumberCardConversion::generateCardByNum($int);
echo '数值卡号: ' . $card . "\r\n";
$num  = NumberCardConversion::generateNumByCard($card);
echo '卡号数值: ' . $num . "\r\n";

// 案例数值: 2584513
// 数值卡号: sy01je819d
// 卡号数值: 2584513

 ?>