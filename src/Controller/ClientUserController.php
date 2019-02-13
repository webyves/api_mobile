<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\ClientUser;
use App\Repository\ClientUserRepository;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class ClientUserController extends AbstractController
{
    /**
     * @Route("/utilisateurs", name="list_client_user")
     */
    public function listClientUser(ClientUserRepository $repo, SerializerInterface $seri)
    {
    	$users = $repo->findAll();
        $data = $seri->serialize($users, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/utilisateur/{id}", name="show_client_user", requirements={"id"="\d+"})
     */
    public function showClientUser(ClientUser $user, SerializerInterface $seri)
    {
        $data = $seri->serialize($user, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/utilisateur/create", name="create_client_user")
     * @Method({"POST"})
     */
    public function createClientUser(Request $request, SerializerInterface $seri, EntityManagerInterface $manager, ClientRepository $clientRepo)
    {
        $data = $request->getContent();
        $user = $seri->deserialize($data, 'App\Entity\ClientUser', 'json');

        // A MODIFIER AVEC AUTENTIFICATION 
        $client = $clientRepo->find(1);
        $user->setClient($client);

        $manager->persist($user);
        $manager->flush();

        return new Response('Utilisateur Créé', Response::HTTP_CREATED);
    }
}
