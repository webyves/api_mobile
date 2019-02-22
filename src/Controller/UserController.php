<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookResponse;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="login_client")
     */
    public function index(Request $request)
    {
		if (!session_id()) {
		    session_start();
		}

		$fb = new Facebook([
		  'app_id' => $this->getParameter('fb.app.id'), 
		  'app_secret' => $this->getParameter('fb.app.secret'),
		  'default_graph_version' => 'v2.2',
		  ]);

		$helper = $fb->getRedirectLoginHelper();

		$permissions = ['email']; // Optional permissions
		$loginUrl = htmlspecialchars($helper->getLoginUrl('https://' . $request->server->get('HTTP_HOST') . '/fb-callback', $permissions));

        return $this->render('user/index.html.twig', [
            'loginUrl' => $loginUrl,
        ]);
    }

    /**
     * @Route("/fb-callback", name="login_client_fb-callback")
     */
    public function fbCallback(EntityManagerInterface $manager, UserRepository $userRepo)
    {
		if (!session_id()) {
		    session_start();
		}

		$fb = new Facebook([
		  'app_id' => $this->getParameter('fb.app.id'), 
		  'app_secret' => $this->getParameter('fb.app.secret'),
		  'default_graph_version' => 'v3.2',
		  ]);

		$helper = $fb->getRedirectLoginHelper();

		try {
		  $accessToken = $helper->getAccessToken();
		  $response = $fb->get('/me?fields=id,name', $accessToken->getValue());
		  $fbUser = $response->getGraphUser();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  // When Graph returns an error
		  echo 'Graph returned an error: ' . $e->getMessage();
		  exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  // When validation fails or other local issues
		  echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  exit;
		}

		if (! isset($accessToken)) {
		  if ($helper->getError()) {
		    header('HTTP/1.0 401 Unauthorized');
		    echo "Error: " . $helper->getError() . "\n";
		    echo "Error Code: " . $helper->getErrorCode() . "\n";
		    echo "Error Reason: " . $helper->getErrorReason() . "\n";
		    echo "Error Description: " . $helper->getErrorDescription() . "\n";
		  } else {
		    header('HTTP/1.0 400 Bad Request');
		    echo 'Bad request';
		  }
		  exit;
		}

		// Logged in
		// echo '<h3>Access Token</h3>';
		// var_dump($accessToken->getValue());

		// The OAuth 2.0 client handler helps us manage access tokens
		$oAuth2Client = $fb->getOAuth2Client();

		// Get the access token metadata from /debug_token
		$tokenMetadata = $oAuth2Client->debugToken($accessToken);
		// echo '<h3>Metadata</h3>';
		// var_dump($tokenMetadata);

		// Validation (these will throw FacebookSDKException's when they fail)
		$tokenMetadata->validateAppId($this->getParameter('fb.app.id')); // Replace {app-id} with your app id
		// If you know the user ID this access token belongs to, you can validate it here
		//$tokenMetadata->validateUserId('123');
		$tokenMetadata->validateExpiration();

		if (! $accessToken->isLongLived()) {
		  // Exchanges a short-lived access token for a long-lived one
		  try {
		    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
		  } catch (Facebook\Exceptions\FacebookSDKException $e) {
		    echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
		    exit;
		  }

		  // echo '<h3>Long-lived</h3>';
		  // var_dump($accessToken->getValue());
		}

		// $_SESSION['fb_access_token'] = (string) $accessToken;
		// User is logged in with a long-lived access token.
		// You can redirect them to a members-only page.
		//header('Location: https://example.com/members.php');


		$user = $userRepo->findOneBy(['fbId'=>$fbUser->getId()]);
		if (!$user) {
			$user = new User();
			$user->setFbId($fbUser->getId());
		}
		$user->setFbName($fbUser->getName())
			 ->setFbToken($accessToken->getValue());
		$manager->persist($user);
		$manager->flush();


        return $this->render('user/fbCallback.html.twig', [
            'fbToken' => $accessToken->getValue(),
            'user' => $user
        ]);

    }

    /**
     * @Route("/api/user", name="show_user", methods={"GET"})
     */
    public function apiShowUser(SerializerInterface $seri)
    {
    	$user = $this->getUser();
        $data = $seri->serialize($user, 'json');
        return JsonResponse::fromJsonString($data);
    }    
}
