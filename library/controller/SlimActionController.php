<?php
namespace library\controller;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

abstract class SlimActionController
{
	/**
	 * @var Container
	 */
	private $container;

	private $data = [];

	private $templateName;

	/**
	 * @param Container $container
	 * @return static
	 */
	public static function create(Container $container)
	{
		return new static($container);
	}

	/**
	 * @param Container $container
	 */
	private function __construct(Container $container)
	{
		$this->container = $container;
		$this->setTemplate();
	}

	/**
	 * @return string
	 */
	protected function getControllerName()
	{
		$class_name = array_pop(explode('\\', get_called_class()));
		return strtolower(str_replace('Controller', '', $class_name));
	}

	/**
	 * @param Request $request
	 * @param Response $response
	 * @param array $args
	 */
	protected abstract function execute(Request $request, Response $response, array $args = []);

	/**
	 * @param Request $request
	 * @param Response $response
	 * @param array $args
	 */
	public function run(Request $request, Response $response, array $args = [])
	{
		$this->execute($request, $response, $args);

		if ($this->templateName) {
			if ($this->getContainer()->has('templates')) {
				$this->getContainer()['templates'][$this->templateName] = $this->data;
			}
			else {
				$this->getContainer()['templates'] = [$this->templateName => $this->data];
			}
		}
		else {
			$response->write(json_encode($this->data));
		}
	}

	/**
	 * @param string $name
	 */
	protected function setTemplate($name = null)
	{
		if (!$name) {
			$name = $this->getControllerName();
		}

		$this->templateName = $name;
	}

	/**
	 * @return Container
	 */
	protected function getContainer()
	{
		return $this->container;
	}

	/**
	 * Assign data
	 * @param $key
	 * @param $value
	 */
	protected function assign($key, $value)
	{
		$this->data[$key] = $value;
	}
}