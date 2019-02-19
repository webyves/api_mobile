<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\ReCpatchaV2;
use App\Service\BileMoEmails;


class PageController extends AbstractController
{
    /**
     * @Route("/mentions", name="mentions")
     */
    public function mentions()
    {
        return $this->render('page/mentions.html.twig');
    }

    /**
     * @Route("/politique", name="politique")
     */
    public function politique()
    {
        return $this->render('page/politique.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, BileMoEmails $emailService)
    {
        if ($request->request->count() > 0) {
            if(ReCpatchaV2::checkValue($request, $this->getParameter('captcha.secretkey'))) {
                $emailService->emailContact($request, $this->getParameter('admin.email'));
                $this->addFlash('success', 'Votre message a été correctement envoyé !');
                return $this->redirectToRoute('login_client');
            }
            $this->addFlash('danger', 'Il y a une erreur avec le captcha !');
            return $this->redirectToRoute('contact', ["captchaSiteKey" => $this->getParameter('captcha.sitekey')]);
        }    	
        return $this->render('page/contact.html.twig', ["captchaSiteKey" => $this->getParameter('captcha.sitekey')]);
    }

}
