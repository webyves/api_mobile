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
use App\Service\ValidCreateUserClient;
use App\Service\JsonListPagination;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as Doc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Component\HttpKernel\EventListener\AbstractSessionListener;

class UserClientController extends AbstractController
{
    /**
     * @Route("/api/clients", name="user_client_list", methods={"GET"})
     * @Doc\Response(
     *     response=200,
     *     description="Get the list of your Clients.<br>Cached for 1 hour."
     *     )
     * )
     * @Doc\Parameter(
     *     name="page",
     *     in="query",
     *     type="integer",
     *     default="1",
     *     description="Number of the Page<br>Example: Add ?page=2 in the Url to get Page 2"
     * )
     * @Doc\Parameter(
     *     name="limit",
     *     in="query",
     *     type="integer",
     *     default="5",
     *     description="Number of Clients per Page<br>Example: Add ?limit=50 in the Url to get 50 Clients"
     * )
     * @Doc\Tag(name="Managing your Clients")
     * @Security(name="Bearer")
     * @Cache(smaxage="3600", public=true)
     */
    public function apiListUserClient(Request $request,UserClientRepository $repo, SerializerInterface $seri)
    {
        $user = $this->getUser();

        $jlp = new JsonListPagination($request, $repo);
        $PaginatedUserClient = $jlp->getUserClientPaginated($user);

        $data = $seri->serialize(
                    $PaginatedUserClient, 
                    'json', 
                    SerializationContext::create()->setGroups(['Default', 'Client_Collection' => ['list']])
                );

        return JsonResponse::fromJsonString($data, 200, [AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER=>true]);
    }

    /**
     * @Route("/api/client/{id}", name="user_client_show", requirements={"id"="\d+"}, methods={"GET"})
     * @Doc\Response(
     *     response=200,
     *     description="Get Details of one of your Clients.<br>Cached until updated.",
     *     @Model(type=UserClient::class)
     *     )
     * )
     * @Doc\Response(
     *     response=403,
     *     description="You are not authorized to get this client's infos."
     * )
     * @Doc\Response(
     *     response=404,
     *     description="This Client does not exist."
     * )
     * @Doc\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="Id of the Client"
     * )
     * @Doc\Tag(name="Managing your Clients")
     * @Security(name="Bearer")
     * @Cache(lastModified="userClient.getUpdatedDate()", public=true)
     */
    public function apiShowUserClient(UserClient $userClient, SerializerInterface $seri)
    {
        $this->denyAccessUnlessGranted('SHOW', $userClient);        
        $data = $seri->serialize($userClient, 'json', SerializationContext::create()->setGroups(array('detail'))->setSerializeNull('true'));
       return JsonResponse::fromJsonString($data,200, [AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER=>true]);

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
        $userClient->setUser($user)
                   ->setCreatedDate(new \DateTime())
                   ->setUpdatedDate(new \DateTime());

        ValidCreateUserClient::checkValue($validator->validate($userClient));

        $emi->persist($userClient);
        $emi->flush();
        return new JsonResponse('CREATION COMPLETED', Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/client/update/{id}", name="user_client_update", requirements={"id"="\d+"}, methods={"POST"})
     * @Doc\Response(
     *     response=200,
     *     description="Client correctly Updated."
     *     )
     * )
     * @Doc\Response(
     *     response=400,
     *     description="Error in json you send see returned message for more infos."
     * )
     * @Doc\Response(
     *     response=403,
     *     description="You are not authorized to get this client's infos."
     * )
     * @Doc\Response(
     *     response=404,
     *     description="This Client does not exist."
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
    public function apiUpdateUserClient(UserClient $userClient, SerializerInterface $seri, Request $request, EntityManagerInterface $emi, ValidatorInterface $validator)
    {
        $this->denyAccessUnlessGranted('UPDATE', $userClient);   
        $data = $request->getContent();
        $userClientInfos = $seri->deserialize($data, 'App\Entity\UserClient', 'json');
        $userClientInfos->setUser($this->getUser())->setCreatedDate(new \DateTime());
        ValidCreateUserClient::checkValue($validator->validate($userClientInfos));
        $userClient->updateFromOther($userClientInfos);
        $emi->persist($userClient);
        $emi->flush();
        return new JsonResponse('UPDATE COMPLETED', Response::HTTP_ACCEPTED);
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
     * @Doc\Response(
     *     response=404,
     *     description="This Client does not exist."
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
