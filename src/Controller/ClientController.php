<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookResponse;
use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\BileMoEmails;
use App\Service\ReCpatchaV2;

class ClientController extends AbstractController
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

        return $this->render('client/index.html.twig', [
            'loginUrl' => $loginUrl,
        ]);
    }

    /**
     * @Route("/fb-callback", name="login_client_fb-callback")
     */
    public function fbCallback(EntityManagerInterface $manager, ClientRepository $clientRepo)
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
		  $user = $response->getGraphUser();
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

		$client = $clientRepo->findOneBy(['fbId'=>$user->getId()]);
		// $fbid_override="123456";
		// $client = $clientRepo->findOneBy(['fbId'=>$fbid_override]);

		if (empty($client)) {
	        return $this->render('client/askForAccount.html.twig', [
	            'fbUser' => $user,
	            'captchaSiteKey' =>  $this->getParameter('captcha.sitekey'),
	        ]);
		}
		$client->setFbToken($accessToken->getValue());
		$manager->persist($client);
		$manager->flush();


        return $this->render('client/fbCallback.html.twig', [
            'fbToken' => $accessToken->getValue(),
            'fbUser' => $user,
            'client' => $client,
        ]);

    }

    /**
     * @Route("/ask-for-account", name="ask_for_account")
     */
    public function askForAccount(Request $request, BileMoEmails $emailService)
    {
    	// VERIFIER LE CAPTCHA
        if(ReCpatchaV2::checkValue($request, $this->getParameter('captcha.secretkey'))) {
	    	// VERIFIER INFOS
	    	// SEND EMAIL WITH INFOS
			$emailService->emailAskForAccount($request, $this->getParameter('admin.email'));
	    	$this->addflash('success','Votre demande de création de compte a bien été envoyée !');
			return $this->redirectToRoute('login_client');
		}
        $this->addFlash('danger', 'Il y a une erreur avec le captcha !');
        return $this->redirectToRoute('login_client');
    }    
}
