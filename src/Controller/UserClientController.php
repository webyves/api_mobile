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
use App\service\ValidCreateUserClient;
use App\Repository\UserClientRepository;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as Doc;

class UserClientController extends AbstractController
{
    /**
     * @Route("/api/clients", name="user_client_list", methods={"GET"})
     * @Doc\Response(
     *     response=200,
     *     description="Get the list of your Clients."
     *     )
     * )
     * @Doc\Tag(name="Managing your Clients")
     * @Security(name="Bearer")
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
     * @Doc\Response(
     *     response=200,
     *     description="Get Details of one of your Clients."
     *     )
     * )
     * @Doc\Response(
     *     response=403,
     *     description="You are not authorized to get this client's infos."
     * )
     * @Doc\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="Id of the Client"
     * )
     * @Doc\Tag(name="Managing your Clients")
     * @Security(name="Bearer")
     */
    public function apiShowUserClient(UserClient $userClient, SerializerInterface $seri)
    {
        $this->denyAccessUnlessGranted('SHOW', $userClient);        
        $data = $seri->serialize($userClient, 'json', SerializationContext::create()->setGroups(array('detail'))->setSerializeNull('true'));
        return JsonResponse::fromJsonString($data);
    }

    /**
     * @Route("/api/client/create", name="user_client_create", methods={"POST"})
     * @Doc\Response(
     *     response=201,
     *     description="Client correctly Created."
     *     )
     * )
     * @Doc\Response(
     *     response=400,
     *     description="Error in json you send see returned message for more infos."
     * )
     * @Doc\Parameter(
     *     name="Client",
     *     in="body",
     *     type="json",
     *     description="All infos of the client",
     *     @Doc\Schema(ref=@Model(type=UserClient::class))
     * )
     * @Doc\Tag(name="Managing your Clients")
     * @Security(name="Bearer")
     */
    public function apiCreateUserClient(SerializerInterface $seri, Request $request, EntityManagerInterface $emi, ValidatorInterface $validator)
    {
        $user = $this->getUser();
        $data = $request->getContent();
        $userClient = $seri->deserialize($data, 'App\Entity\UserClient', 'json');
        $userClient->setUser($user);
        $userClient->setCreatedDate(new \DateTime());

        ValidCreateUserClient::checkValue($validator->validate($userClient));

        $emi->persist($userClient);
        $emi->flush();
        return new JsonResponse('CREATION COMPLETED', Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/client/delete/{id}", name="user_client_delete", methods={"GET"}, requirements={"id"="\d+"})
     * @Doc\Response(
     *     response=200,
     *     description="Delete one of your Client."
     * )
     * @Doc\Response(
     *     response=403,
     *     description="You are not authorized to delete this client."
     * )
     * @Doc\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="Id of the Client"
     * )
     * @Doc\Tag(name="Managing your Clients")
     * @Security(name="Bearer")
     */
    public function apiDeleteUserClient(UserClient $userClient, EntityManagerInterface $emi)
    {
        $this->denyAccessUnlessGranted('DELETE', $userClient);        
        $emi->remove($userClient);
	    $emi->flush();
        return new JsonResponse('DELETION COMPLETED', Response::HTTP_ACCEPTED);
    }
}
