<?php
namespace library\api;

use Zend\Http\Client;

class APIReader {

	private $httpClient;

	private $host = 'http://api.buildyourcity.de/';

	private $endpoint;

	/**
	 * @param string $endpoint
	 */
	function __construct($endpoint)
	{
		$this->httpClient = new Client();
		$this->endpoint = $endpoint;
	}

	/**
	 * @param int|null $id
	 * @return mixed|null
	 */
	public function get($id = null)
	{
		//emulate non-existing API
		if (file_exists($this->getJSONPath())) {
			$json = json_decode(file_get_contents($this->getJSONPath()));

			return $json;
		}
		else {
			$url = $this->host . $this->endpoint;

			if ($id) {
				$url .= '/' . $id;
			}

			$this->httpClient->setUri($url);
			$response = json_decode($this->httpClient->send()->getBody());

			if ($response->status == "OK") {
				return $response->result;
			}
		}

		return null;
	}

	/**
	 * @return string
	 */
	private function getJSONPath()
	{
		$path = explode(DIRECTORY_SEPARATOR, __DIR__);

		$path = array_splice($path, 0, count($path) - 2);

		$path = implode(DIRECTORY_SEPARATOR, $path);

		$path = implode(DIRECTORY_SEPARATOR, [$path, 'static' , $this->endpoint . '.json']);

		return $path;
	}
}