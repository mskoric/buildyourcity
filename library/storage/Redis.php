<?php
namespace library\storage;

class Redis {

	protected static $_instance = null;

	private $redis;


	/**
	 * Singleton, return instance when available or create new one
	 * @return Redis
	 **/
	public static function getInstance()
	{
		if (null === self::$_instance)
		{
			self::$_instance = new self;
		}
		return self::$_instance;
	}

	private function __construct()
	{
		$this->redis = new \Redis();
		$this->redis->connect('localhost');
	}

	/**
	 * @param string $key
	 * @return bool|string
	 */
	public function get($key)
	{
		return $this->redis->get($key);
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @return mixed
	 */
	public function set($key, $value)
	{
		return $this->redis->set($key, $value);
	}

	/**
	 * @param array $keys
	 * @return array
	 */
	public function mget($keys)
	{
		return $this->redis->mget($keys);
	}


	/**
	 * @param $key
	 * @param $timestamp
	 * @return bool
	 */
	public function expireAt($key, $timestamp)
	{
		return $this->redis->expireAt($key, $timestamp);
	}
}