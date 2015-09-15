<?php
namespace library\storage\repository;


use library\api\APIReader;
use library\storage\reader\StorageReader;
use library\storage\writer\StorageWriter;

abstract class AbstractRepository
{
	/**
	 * @var string
	 */
	protected $prefix;

	/**
	 * @var string
	 */
	protected $endpoint;

	/**
	 * @var bool
	 */
	protected $useList = false;

	/**
	 * @param string|null $key optional
	 * @return mixed|null
	 */
	public function get($key = null)
	{
		$storageReader = new StorageReader($this->prefix);

		$value = $storageReader->read($key);

		if (!$value) {

			$apiReader = new APIReader($this->endpoint);

			$value = $apiReader->get($key);

			if ($this->validate($value)) {

				$value = $this->convert($value);

				if ($value) {

					$storageWriter = new StorageWriter($this->prefix);

					$value = $storageWriter->write($key, $value);
				}
			}
		}

		return $value;
	}

	public function getList ($offset = null, $count = null)
	{
		$storageReader = new StorageReader($this->prefix);

		$value = $storageReader->readList($offset, $count);

		if (!$value) {

			$apiReader = new APIReader($this->endpoint);

			$value = $apiReader->get();

			if ($this->validate($value)) {

				$value = $this->convert($value);

				if ($value) {

					$storageWriter = new StorageWriter($this->prefix);

					$value = $storageWriter->writeList($value);
				}
			}
		}

		return $value;
	}

	/**
	 * @param $value
	 * @return boolean
	 **/
	protected abstract function validate($value);

	/**
	 * @param $value
	 * @return mixed
	 **/
	protected abstract function convert($value);

}