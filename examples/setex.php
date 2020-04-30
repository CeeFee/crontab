<?php
namespace ceefee\crontab;

require __DIR__ .'/../src/RedisInstance.php';

$redis = new RedisInstance();
$redis->connect();
$redis->setex('crontab:123:456', 100);
$redis->close();