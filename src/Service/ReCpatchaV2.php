<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class ReCpatchaV2
{
    static public function checkValue(Request $request, $captchaSecretKey)
    {
        $api_url = "https://www.google.com/recaptcha/api/siteverify?secret="
            . $captchaSecretKey
            . "&response=" . $request->request->get('g-recaptcha-response')
            . "&remoteip=" . $request->getClientIp();
        $decode = json_decode(file_get_contents($api_url), true);
        return $decode['success'];
    }
}
