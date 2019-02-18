<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class BileMoEmails
{
    private $mailer;
    private $twig;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig) {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function emailAskForAccount(Request $request, $toEmail)
    {
        $message = (new \Swift_Message('BileMo - Demande Ouverture de Compte'))
            ->setFrom(strip_tags($request->request->get('contactEmail')))
            ->setTo($toEmail)
            ->setBody(
                $this->twig->render(
                    'emails/emailAskForAccount.html.twig',
                    array(
                        'contactName' => strip_tags($request->request->get('contactContactName')),
                        'fbId' => strip_tags($request->request->get('contactFbId')),
                        'username' => strip_tags($request->request->get('contactUsername')),
                        'address' => strip_tags($request->request->get('contactAddress')),
                        'email' => strip_tags($request->request->get('contactEmail'))
                    )
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }
}
