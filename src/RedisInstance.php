<?php
namespace ceefee\crontab;

class RedisInstance
{
	private $redis;
	
	public function __construct()
	{
		$this->checkEnvironment();
	}
    
    public function __destruct()
    {
        $this->close();
    }
	
	public function setRedis($redis)
	{
		$this->redis = $redis;
	}
    
    public function connect($server = '127.0.0.1', $port = 6379)
    {
        $this->redis = new \Redis();
        $this->redis->connect($server, $port);
    }
    
    public function close()
    {
        $this->redis->close();
    }
	
	public function setex($keyName, $timeout, $value = null)
	{
		$this->setNotifyKeyspaceEvents();
		$this->redis->setex($keyName, $timeout, $value);
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
		
		$this->setOption();
		$this->redis->psubscribe($patterns, $callback);
	}
	
	protected function setOption()
	{
		$this->redis->setOption(\Redis::OPT_READ_TIMEOUT, -1);
	}
	
	protected function setNotifyKeyspaceEvents()
	{
		$this->redis->config('SET', 'notify-keyspace-events', 'Ex');
	}
    
    protected function checkEnvironment()
    {
        if (!extension_loaded('redis')) {
            throw new \Exception('Redis extension not loaded'); 
        }
    }
}