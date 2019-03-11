<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Session\Session;

class FBService
{
    private $fb;
    private $fbAppId;
    private $fbAppSecret;

    public function __construct($fbAppId, $fbAppSecret)
    {
        $this->fbAppId = $fbAppId;
        $this->fbAppSecret = $fbAppSecret;
    }

    private function launchFB()
    {
        // FACEBOOK-SDK NEED TO STOCK INTEL IN $_SESSION
        // CAN'T USE SYMFONY SESSION SYSTEM WITHOUT MODIFING THE FB-SDK
        if (!session_id()) {
            session_start();
        }

        $fb = new Facebook([
          'app_id' => $this->fbAppId,
          'app_secret' => $this->fbAppSecret,
          'default_graph_version' => 'v3.2',
          ]);

        $this->fb = $fb;
    }

    public function genLoginUrl(Request $request)
    {
        $this->launchFB();
        $helper = $this->fb->getRedirectLoginHelper();

        $permissions = ['email']; // Optional permissions
        $loginUrl = htmlspecialchars($helper->getLoginUrl('https://' . $request->server->get('HTTP_HOST') . '/fb-callback', $permissions));
        return $loginUrl;
    }

    public function fbLogin()
    {
        $this->launchFB();
        $helper = $this->fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
            $response = $this->fb->get('/me?fields=id,name', $accessToken->getValue());
            $fbUser = $response->getGraphUser();
        } catch (FacebookResponseException $e) {
            // When Graph returns an error
            throw new HttpException(400, 'Graph returned an error: ' . $e->getMessage());
        } catch (FacebookSDKException $e) {
            // When validation fails or other local issues
            throw new HttpException(400, 'Facebook SDK returned an error: ' . $e->getMessage());
        }

        if (! isset($accessToken)) {
            if ($helper->getError()) {
                $message = "Error: " . $helper->getError() . "\n"
                . "Error Code: " . $helper->getErrorCode() . "\n"
                . "Error Reason: " . $helper->getErrorReason() . "\n"
                . "Error Description: " . $helper->getErrorDescription() . "\n";
                throw new HttpException(401, $message);
            } else {
                throw new HttpException(400, 'Bad request');
            }
        }

        // Logged in

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $this->fb->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);

        // Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId($this->fbAppId); // Replace {app-id} with your app id
        // If you know the user ID this access token belongs to, you can validate it here
        $tokenMetadata->validateExpiration();

        if (! $accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (FacebookSDKException $e) {
                throw new HttpException(400, "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n");
            }
        }

        return ["fbUser" => $fbUser, "accessToken" => $accessToken];
    }
}
