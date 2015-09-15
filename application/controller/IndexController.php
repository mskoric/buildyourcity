<?php
namespace application\controller;

use library\controller\SlimActionController;
use library\model\CN;
use library\model\Prices;
use library\model\Region;
use library\model\Support;
use library\storage\repository\CNRepository;
use library\storage\repository\PricesRepository;
use library\storage\repository\RegionRepository;
use library\storage\repository\SupportRepository;
use Slim\Http\Response;
use Slim\Http\Request;

class IndexController extends SlimActionController {

	protected function execute(Request $request, Response$response, array $args = [])
	{
		$this->setTemplate('index');
		$mainHeading = 'BuildYourCity!';

		$this->assign('attributes', array(
			'site' => 'index',
			'title' => 'BuildYourCity ist das Portal zum erbauen deiner Stadt. Sei Teil von etwas großem',
			'mainHeading' => $mainHeading,
			'subHeading' => 'Jetzt registrieren und Teil der Erbauer deiner Stadt sein',
		));

		$this->assign('support', array(
			'freePhone' => Support::FREEPHONE,
			'landline' => Support::LANDLINE,
			'fax' => Support::FAX,
			'mail' => Support::MAIL
		));
	}
}