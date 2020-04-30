<?php
namespace ceefee\crontab;

require __DIR__ .'/../src/RedisInstance.php';

echo "订阅消息监听\n";

$redis = new RedisInstance();
$redis->connect();
$redis->psubscribe(0, function($redis, $pattern, $channel, $msg) {
    echo "Pattern: {$pattern}\n";
    echo "Channel: {$channel}\n";
    echo "Msg: {$msg}\n";
});
$redis->close();