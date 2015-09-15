<?php
namespace application;

use application\controller\IndexController;
use application\controller\PricesController;
use application\controller\JoinController;
use library\storage\repository\FeedbackRepository;
use library\storage\repository\RegionRepository;
use application\controller\FeedbackController;
use library\storage\writer\RegionStorageWriter;
use library\storage\reader\StorageReader;
use Slim\App;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\TwigExtension;

class Application
{

	/**
	 * @var App
	 */
	private $slim;

	public function __construct()
	{

		$this->slim = new App();

		$this->slim->add($this->initRenderer());

		$this->slim->get('/', function (Request $request, Response $response, $args) {
			return IndexController::create($this->getContainer())->run($request, $response, $args);
		})->setName('index');

		$this->slim->post('/', function (Request $request, Response $response, $args) {
			return json_encode(array('test'=>'bla'));
		})->setName('test');
	}

	private function initRenderer()
	{
		$projectPath = $this->getProjectPath();

		return function (Request $request, Response $response, $next) use ($projectPath) {
			/* @var Container $container */
			$container = $this;

			/* @var Response $response */
			$response = $next($request, $response);

			if ($container->has('templates')) {

				if (!$container->has('view')) {
					$view = new \Slim\Views\Twig(implode(DIRECTORY_SEPARATOR, [$projectPath, 'application', 'view']));
					$view->addExtension(new TwigExtension(
						$container->get('router'),
						$container->get('request')->getUri()
					));

					$container->register($view);
				}

				$templates = $container->get('templates');
				foreach ($templates as $name => $data) {
					$response = $container->get('view')->render($response, $name . '.twig', $data);
				}
			}
			else {
				$response->withHeader('Content-Type', 'application/json;charset=utf-8');
			}
			return $response;
		};
	}

	/**
	 * Run the application
	 */
	public function run()
	{
		$this->slim->run();
	}

//	/**
//	 * Load config by name
//	 * Convention: config file MUST return plain php array
//	 *
//	 * @param $name
//	 * @return array
//	 */
//	private function loadConfig($name)
//	{
//		$config_file = implode(DIRECTORY_SEPARATOR, [$this->getProjectPath(), 'config' , $name . '.php']);
//
//		if (file_exists($config_file)) {
//			return include_once $config_file;
//		}
//
//		return [];
//	}

	private function getProjectPath()
	{
		return dirname(__DIR__);
	}

//	/**
//	 * @param array $config
//	 * @return \Twig_Environment
//	 */
//	private function createView()
//	{
//		// Fetch DI Container
//		/** @var Container $container */
//		$container = $this->slim->getContainer();
//
//		// Instantiate and add Slim specific extension
//		$view = new \Slim\Views\Twig(implode(DIRECTORY_SEPARATOR, [$this->getProjectPath(), 'application', 'view']));
//
//		$view->addExtension(new TwigExtension(
//			$container->get('router'),
//			$container->get('request')->getUri()
//		));
//
//		$container->register($view);
//	}
}