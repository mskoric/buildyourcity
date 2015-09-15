<?php
namespace application\controller;

use library\controller\SlimActionController;
use library\model\CN;
use library\model\Support;
use library\model\PromotedFeedback;
use library\model\Feedback;
use library\storage\repository\CNRepository;
use library\storage\repository\FeedbackRepository;
use library\storage\repository\SupportRepository;
use library\storage\repository\PromotedFBRepository;
use Slim\Http\Response;
use Slim\Http\Request;

class FeedbackController extends SlimActionController {

	protected function execute(Request $request, Response$response, array $args = [])
	{
		$this->setTemplate('feedback');


		// get CNR key figure data
		$cnr = new CNRepository();

		/* @var CN $cnrData */
		$cnrData = $cnr->get();
		$hosts = $cnrData->getHosts();

		$mainHeading = 'Vermieter empfehlen Traum-Ferienwohnungen!';
		$subHeading = 'Über ' . $this->numberFormat($hosts->count) . ' Vermieter sind schon Kunde ‒ wir freuen uns auf Sie.';

			$this->assign('attributes', array(
			'site' => 'feedback',
			'title' => 'Von Vermieter getestet und empfohlen - Vermieterstimmen auf Traum-Ferienwohnungen.de',
			'mainHeading' => $mainHeading,
			'subHeading' => $subHeading,
		));

		// get promoted feedback data
		$promotedFB = new PromotedFBRepository();

		/* @var PromotedFeedback $promotedFeedbackData */
		$promotedFeedbackData = $promotedFB->get();
		$promotedFeedback = $promotedFeedbackData->getFeedback();

		$this->assign('promotedFeedback', $promotedFeedback);


		// get common feedback data
		$feedback = new FeedbackRepository();

		/* @var Feedback $feedbackData */
		$commonFeedback = $feedback->getList();

		$this->assign('commonFeedback', $commonFeedback);


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