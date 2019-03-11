<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class BileMoEmails
{
    private $mailer;
    private $twig;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function emailContact(Request $request, $toEmail)
    {
        $message = (new \Swift_Message('BileMo - Formulaire de contact'))
            ->setFrom(strip_tags($request->request->get('contactEmail')))
            ->setTo($toEmail)
            ->setBody(
                $this->twig->render(
                    'emails/emailContact.html.twig',
                    array(
                        'name' => strip_tags($request->request->get('contactFirstname')) . " " . strip_tags($request->request->get('contactLastname')),
                        'subject' => strip_tags($request->request->get('contactSubject')),
                        'message' => strip_tags($request->request->get('contactMessage'))
                    )
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }
}
