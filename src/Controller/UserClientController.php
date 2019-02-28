<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
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
        $clients = $repo->findBy(["user" => $user]);
        $data = $seri->serialize($clients, 'json', SerializationContext::create()->setGroups(array('list')));
        return JsonResponse::fromJsonString($data);
    }

    /**
     * @Route("/api/client/{id}", name="user_client_show", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function apiShowUserClient(UserClient $userClient, SerializerInterface $seri)
    {
        $user = $this->getUser();
        if ($user !== $userClient->getUser()) {
        	return new Response('UNAUTHORIZED ACTION !', Response::HTTP_UNAUTHORIZED);
        }
        $data = $seri->serialize($userClient, 'json', SerializationContext::create()->setGroups(array('detail'))->setSerializeNull('true'));
        return JsonResponse::fromJsonString($data);
    }

    /**
     * @Route("/api/client/create", name="user_client_create", methods={"POST"})
     */
    public function apiCreateUserClient(SerializerInterface $seri, Request $request, EntityManagerInterface $emi, ValidatorInterface $validator)
    {
        $user = $this->getUser();
        $data = $request->getContent();
        $userClient = $seri->deserialize($data, 'App\Entity\UserClient', 'json');
        $userClient->setUser($user);
        $userClient->setCreatedDate(new \DateTime());

        $errors = $validator->validate($userClient);
        if (count($errors)) {
            $errMsg = "";
            foreach ($errors as $violation) {
                $errMsg .= $violation->getPropertyPath() . " : " .$violation->getInvalidValue() . " " . $violation->getMessageTemplate().'<br>';
            }

            return new Response('ERROR IN DATA !<br>'. $errMsg, Response::HTTP_BAD_REQUEST);
        }

        $emi->persist($userClient);
        $emi->flush();        
        return new Response('CREATION COMPLETED', Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/client/delete/{id}", name="user_client_delete", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function apiDeleteUserClient(UserClient $userClient, EntityManagerInterface $emi)
    {
        $user = $this->getUser();
        if ($user !== $userClient->getUser()) {
        	return new Response('UNAUTHORIZED ACTION !', Response::HTTP_UNAUTHORIZED);
        }
		$emi->remove($userClient);
	    $emi->flush();
        return new Response('DELETION COMPLETED', Response::HTTP_ACCEPTED);
    }
}
