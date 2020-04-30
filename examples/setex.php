<?php
require __DIR__ .'/../src/Redis.php';

use ceefee\crontab\Redis;

$redis = Redis::getInstance();
$redis->connect();
$redis->setex('crontab:123:456', 100);
$redis->close();