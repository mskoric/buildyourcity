<?php
namespace application\controller;

use library\controller\SlimActionController;
use library\model\CN;
use library\model\Support;
use library\storage\repository\CNRepository;
use library\storage\repository\SupportRepository;
use Slim\Http\Response;
use Slim\Http\Request;

class JoinController extends SlimActionController {

	protected function execute(Request $request, Response$response, array $args = [])
	{
		$this->setTemplate('join');

		$mainHeading = 'Ferienwohnung anmelden in 3 Schritten!';


		// get CNR key figure data
		$cnr = new CNRepository();

		/* @var CN $cnrData */
		$cnrData = $cnr->get();
		$users  = $cnrData->getUsers();
		$inquiries = $cnrData->getInquiries();
		$hosts = $cnrData->getHosts();

		$keyFigures = array(
			'users' => $this->numberFormat($users->count),
			'inquiries' => $this->numberFormat($inquiries->count),
			'hosts' => $this->numberFormat($hosts->count),
			'year' => $cnrData->getYear()
		);

		$this->assign('attributes', array(
			'site' => 'join',
			'title' => 'Ferienhäuser und Ferienwohnungen anmelden - unverbindlich und kostenlos auf Traum-Ferienwohnungen.de',
			'mainHeading' => $mainHeading,
			'subHeading' => 'Erstellen Sie Ihr Inserat und erhalten noch heute die erste Buchung.',
			'keyFigures' => $keyFigures
		));


		// get support data
		$support = new SupportRepository();

		/* @var Support $supportData */
		$supportData = $support->get();

		$this->assign('support', array(
			'freePhone' => $supportData->getFreePhone(),
			'landline' => $supportData->getLandline(),
			'fax' => $supportData->getFax(),
			'mail' => $supportData->getMail()
		));
	}

	protected function numberFormat($n) {
		if ($n > 1000000) {
			return round(($n/1000000),1).' Mio';
		} else if($n > 1000) {
			return number_format(round(($n/1000)) * 1000, 0, '', '.');
		}

		return number_format($n);
	}
}