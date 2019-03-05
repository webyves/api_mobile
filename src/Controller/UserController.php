<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use App\Service\FBService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as Doc;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="login_client")
     */
    public function index(Request $request, FBService $fbs)
    {
        return $this->render('user/index.html.twig', [
            'loginUrl' => $fbs->genLoginUrl($request),
        ]);
    }

    /**
     * @Route("/fb-callback", name="login_client_fb-callback")
     */
    public function fbCallback(EntityManagerInterface $manager, UserRepository $userRepo, FBService $fbs)
    {

    	$fbInfos = $fbs->fbLogin();

		$user = $userRepo->findOneBy(['fbId' => $fbInfos['fbUser']->getId()]);
		if (!$user) {
			$user = new User();
			$user->setFbId($fbInfos['fbUser']->getId());
		}
		$user->setFbName($fbInfos['fbUser']->getName())
			 ->setFbToken($fbInfos['accessToken']->getValue());
		$manager->persist($user);
		$manager->flush();


        return $this->render('user/fbCallback.html.twig', [
            'fbToken' => $fbInfos['accessToken']->getValue(),
            'user' => $user
        ]);

    }

    /**
     * @Route("/api/user", name="show_user", methods={"GET"})
     * @Doc\Response(
     *     response=200,
     *     description="Get your infos and list of all your Clients.",
     *     @Model(type=User::class)
     * )
     * @Doc\Tag(name="Your Infos")
     * @Security(name="Bearer")
     */
    public function apiShowUser(SerializerInterface $seri)
    {
    	$user = $this->getUser();
        $data = $seri->serialize($user, 'json');
        return JsonResponse::fromJsonString($data);
    }    
}
