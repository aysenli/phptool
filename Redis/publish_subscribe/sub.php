<?php
/**
 * Redis subscribe(消息订阅端)
 * User: Aysen
 * Mail: 122195562@qq.com
 * Date: 2018/4/20
 * Time: 13:57
 */
$redis = new Redis();
$redis->pconnect('192.168.10.185', 6379);
/**
 * 报错信息：
 * Fatal error: Uncaught RedisException: read error on connection
 * RedisException: read error on connection
 * 追踪代码：strace php sub.php
 * 原因分析: poll设置接收超时所致，默认是60s。
 * 解决方式：
 * 1. 在代码起始处设置 ini_set('default_socket_timeout', -1); 全局设置，不推荐
 * 2. 在redis connect后执行 $redis->setOption(Redis::OPT_READ_TIMEOUT, -1); 只会影响到redis本身，推荐
 * 两种方法中的-1均表示永不超时，你也可以将超时设置为自己希望的时间
 */
$redis->setOption(Redis::OPT_READ_TIMEOUT, -1);
$redis->auth('auth');
$redis->subscribe(array('channelName'), 'callback');
// 回调函数, 这里写处理逻辑
function callback($instance, $channelName, $message) {
	echo $channelName, "==>", $message, PHP_EOL;
}


?>