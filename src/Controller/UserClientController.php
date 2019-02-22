<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\UserClient;
use App\Repository\UserClientRepository;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;

class UserClientController extends AbstractController
{
    /**
     * @Route("/api/clients", name="user_client_list", methods={"GET"})
     */
    public function apiListUserClient(UserClientRepository $repo, SerializerInterface $seri)
    {
        $user = $this->getUser();
        $clients = $repo->findBy("userId" => $user->getId());
        $data = $seri->serialize($clients, 'json', SerializationContext::create()->setGroups(array('list')));
        return JsonResponse::fromJsonString($data);
    }

    /**
     * @Route("/api/client/{id}", name="user_client_show", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function apiShowUserClient(UserClient $UserClient, SerializerInterface $seri)
    {
        $user = $this->getUser();
        // VERIFIER QUE LE CLIENT APPARTIENT BIEN AU USER
        $data = $seri->serialize($UserClient, 'json', SerializationContext::create()->setGroups(array('detail')));
        return JsonResponse::fromJsonString($data);
    }

    /**
     * @Route("/api/client/create", name="user_client_create", methods={"POST"})
     */
    public function apiCreateUserClient(SerializerInterface $seri, Request $request)
    {
        $user = $this->getUser();
        // $clients = $repo->findBy("userId" => $user->getId());
        // $data = $seri->serialize($clients, 'json', SerializationContext::create()->setGroups(array('list')));
        $data=null;
        return JsonResponse::fromJsonString($data);
    }

    /**
     * @Route("/api/client/delete", name="user_client_delete", methods={"POST"})
     */
    public function apiDeleteUserClient(SerializerInterface $seri, Request $request)
    {
        $user = $this->getUser();
        // $clients = $repo->findBy("userId" => $user->getId());
        // $data = $seri->serialize($clients, 'json', SerializationContext::create()->setGroups(array('list')));
        $data=null;
        return JsonResponse::fromJsonString($data);
    }
}
