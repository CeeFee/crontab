<?php
namespace ceefee\crontab;

class Redis
{
    private static $_redis;
    private static $_instance = null;
    
    private function __construct()
    {
        $this->checkEnvironment();
    }
    
    private function __clone() {}
    
    public static function getInstance()
    {
        if (!(self::$_instance instanceof Redis)) {
            self::$_instance = new Redis();
        }
        return self::$_instance;
    }
    
    public function setRedis($redis)
    {
        self::$_redis = $redis;
        
        return self::$_instance;
    }
    
    public function connect($server = '127.0.0.1', $port = 6379)
    {
        self::$_redis = new \Redis();
        self::$_redis->connect($server, $port);
        
        return self::$_instance;
    }
    
    public function close()
    {
        self::$_redis->close();
        
        return self::$_instance;
    }
    
    public function setex($keyName, $timeout, $value = null)
    {
        self::setNotifyKeyspaceEvents();
        self::$_redis->setex($keyName, $timeout, $value);
        
        return self::$_instance;
    }
    
    public function psubscribe($database, $callback)
    {
        $pattern = '__keyevent@%s__:expired';
        $patterns = [];
        
        if (is_array($database)) {
            foreach ($database as $db) {
                $patterns[] = sprintf($pattern, $db);
            }
        } else {
            $patterns[] = sprintf($pattern, $database);
        }
        
        self::setOption();
        self::$_redis->psubscribe($patterns, $callback);
        
        return self::$_instance;
    }
    
    protected static function setOption()
    {
        self::$_redis->setOption(\Redis::OPT_READ_TIMEOUT, -1);
    }
    
    protected static function setNotifyKeyspaceEvents()
    {
        self::$_redis->config('SET', 'notify-keyspace-events', 'Ex');
    }
    
    protected function checkEnvironment()
    {
        if (!extension_loaded('redis')) {
            throw new \Exception('Redis extension not loaded');
        }
    }
}