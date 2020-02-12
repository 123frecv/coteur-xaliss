<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['POST', 'PUT'])
            && $subject instanceof User;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        
        if (!$user instanceof UserInterface) {
            return false;
        }
        
        if($user->getRoles()[0] === 'ROLE_SUP_ADMIN' && 
        ($subject->getRoles()[0] != 'ROLE_SUP_ADMIN'))
         {
            return true;
         }
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'POST':
                if($userconnect->getRoles()[0] === 'ROLE_ADMIN' && 
                ($subject->getRoles()[0] === 'ROLE_CAISSIER'||
                $subject->getRoles()[0] === 'ROLE_PARTENAIRE'))
                 {
                   return true;
                 }elseif($userconnect->getRoles()[0] === 'ROLE_CAISSIER')
                 {
                   return false;
                 }
                 if($userconnect->getRoles()[0] === 'ROLE_PARTENAIRE' && 
                 ($subject->getRoles()[0] === 'ROLE_ADMIN_PARTENAIRE'||
                 $subject->getRoles()[0] === 'ROLE_USER_PARTENAIRE'))
                  {
                    return true;
                  }

                break;
            case 'POST_VIEW':
                if($userconnect->getRoles()[0] === 'ROLE_CAISSIER')
                {
                  return false;
                }
                break;
        }

        return false;
    }
}
