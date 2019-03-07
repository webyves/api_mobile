<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserClientVoter extends Voter
{
    protected function supports($attribute, $userClient)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['SHOW', 'DELETE', 'UPDATE'])
            && $userClient instanceof \App\Entity\UserClient;
    }

    protected function voteOnAttribute($attribute, $userClient, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'SHOW':
            case 'UPDATE':
            case 'DELETE':
                return $user == $userClient->getUser();
                break;
        }

        return false;
    }
}
