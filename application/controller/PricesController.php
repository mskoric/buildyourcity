<?php
namespace application\controller;

use library\controller\SlimActionController;
use library\model\CN;
use library\model\Prices;
use library\model\Support;
use library\storage\repository\CNRepository;
use library\storage\repository\PricesRepository;
use library\storage\repository\SupportRepository;
use Slim\Http\Response;
use Slim\Http\Request;

class PricesController extends SlimActionController {

	protected function execute(Request $request, Response$response, array $args = [])
	{
		$this->setTemplate('prices');

		$mainHeading = 'Ferienwohnung eintragen und direkt anbieten!';


		// get CNR key figure data
		$cnr = new CNRepository();

		/* @var CN $cnrData */
		$cnrData = $cnr->get();
		$guests	= $cnrData->getGuests();
		$guests = $this->numberFormat($guests->count);

		$this->assign('attributes', array(
			'site' => 'prices',
			'title' => 'Ferienhäuser und Ferienwohnungen anbieten - jetzt online eintragen auf Traum-Ferienwohnungen.de',
			'mainHeading' => $mainHeading,
			'subHeading' => 'Mit unserem Exposé wird Ihre Ferienwohnung perfekt präsentiert.',
			'guests' => $guests
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


		// get prices data
		$prices = new PricesRepository();

		/* @var Prices $pricesData */
		$pricesData = $prices->get();

		$this->assign('prices', array(
			'first' => $pricesData->getFirst(),
			'further' => $pricesData->getFurther()
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