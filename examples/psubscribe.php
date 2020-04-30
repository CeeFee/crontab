<?php
require __DIR__ .'/../src/Redis.php';

use ceefee\crontab\Redis;

echo "订阅消息监听\n";

$redis = Redis::getInstance();
$redis->connect();
$redis->psubscribe(0, function($redis, $pattern, $channel, $msg) {
    echo "Pattern: {$pattern}\n";
    echo "Channel: {$channel}\n";
    echo "Msg: {$msg}\n";
});
$redis->close();