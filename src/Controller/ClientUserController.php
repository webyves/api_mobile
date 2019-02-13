<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ClientUserController extends AbstractController
{
    /**
     * @Route("/client/user", name="client_user")
     */
    public function index()
    {
        return $this->render('client_user/index.html.twig', [
            'controller_name' => 'ClientUserController',
        ]);
    }
}
