<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\MailingService;

class ApiContactController extends Controller
{

	private $mailingService;

	public function __construct(MailingService $mailingService) {
        $this->mailingService = $mailingService;
    }

	 /**
     * @Route("/api/mailing/send_contact_mail", name="send_contact_mail", methods={"POST"})
     */
    public function sendcontactMailAction( Request $request)
    {
    	$content = json_decode($request->getContent(), true);

    	$mail = $content['visitorEmail'];
    	$messageContent = $content['visitorMessage'];

    	$this->mailingService->sendEmail($mail, $messageContent);

    	$response = new Response('email sent with success');


		return $response;
    }
}