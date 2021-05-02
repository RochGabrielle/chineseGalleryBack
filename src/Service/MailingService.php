<?php
// src/Service/MailingService.php
namespace App\Service;

use Twig\Environment;

class MailingService 
{

	protected $twig;
    protected $mailer;

    public function __construct(Environment $twig, \Swift_Mailer $mailer)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

	/**
	*Send email with swiftmailer to website email adress set in .env
	*@var string $visitorEmail adress
	* @var string $messageContent
	**/
	public function sendEmail(string $visitorEmail, string $messageContent)
	{
	    $message = (new \Swift_Message('Hello Email'))
	        ->setFrom($visitorEmail)
	        ->setTo('lucyliushcn@gmail.com')
	        ->setBody(
	            $this->twig->render(
	                // templates/emails/contact.html.twig
	                'emails/contact.html.twig',
	                ['name' => $visitorEmail,
	            	 'message' => $messageContent
	            	]
	            ),
	            'text/html'
	        )
	    ;

	   $this->mailer->send($message);
	}
}