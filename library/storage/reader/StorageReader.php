<?php
namespace library\storage\reader;

use library\storage\Redis;

class StorageReader {

	/* @var string $prefix */
	private $prefix;

	/* @var Redis $redis */
	protected $redis;

	/**
	 * @param string $prefix
	 */
	function __construct($prefix)
	{
		$this->prefix = $prefix;
		$this->redis = Redis::getInstance();
	}

	/**
	 * @param string $key
	 * @return mixed|null
	 **/
	public function read($key)
	{
		$key = $key ? $this->prefix . $key : $this->prefix;

		$value = $this->redis->get($key);

		if ($value) {
			$value = unserialize($value);
		}

		return $value;
	}

	/**
	 * @param int $offset
	 * @param int $count
	 * @return array|null
	 */
	public function readList($offset, $count)
	{
		$keys = $this->read('_keys');

		if ($keys) {
			$keys = array_slice($keys, $offset, $count);

			$elements = $this->redis->mget($keys);

			if ($elements) {
				foreach ($elements as $key => $element) {
					$elements[$key] = unserialize($element);
				}
			}

			return $elements;
		}
		return null;
	}
}