<?php
/**
 * Redis publish(消息发布端)
 * User: Aysen
 * Mail: 122195562@qq.com
 * Date: 2018/4/20
 * Time: 13:57
 */
$redis = new Redis();
$redis->pconnect('192.168.10.185', 6379);
$redis->auth('auth');
$redis->publish('channelName','hello world ' . date("Y-m-d H:i:s", time()));


?>