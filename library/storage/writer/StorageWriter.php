<?php
namespace library\storage\writer;

use library\storage\Redis;

class StorageWriter {

	/* @var string $prefix */
	protected $prefix;

	/* @var Redis $redis */
	protected $redis;

	/**
	 * @param string $prefix
	 */
	public function __construct($prefix)
	{
		$this->prefix = $prefix;
		$this->redis = Redis::getInstance();
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @return mixed
	 **/
	public function write($key, $value)
	{
		$key = $key ? $this->prefix . $key : $this->prefix;

		$this->redis->set($key, serialize($value));

		$this->expire($this->prefix . $key);

		return $value;
	}

	/**
	 * @param array $list
	 * @return mixed
	 */
	public function writeList($list) {
		$keys = array_keys($list);
		foreach ($keys as $index => $key) {
			$keys[$index] = $this->prefix . $key;
		}

		$this->write('_keys', $keys);

		foreach ($list as $key => $value) {
			$this->write($key, $value);
		};

		return $list;
	}

	/**
	 * @param $key
	 */
	private function expire($key)
	{
		//expire in one week
		$this->redis->expireat($key, strtotime("+1 week"));
	}
}