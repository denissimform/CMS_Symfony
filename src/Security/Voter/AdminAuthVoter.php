<?php

namespace App\Security\Voter;

use App\Entity\Department;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User;

/** 
 * @method User getUser() 
 * */
class AdminAuthVoter extends Voter
{
    public const EDIT = 'EDIT';
    public const VIEW = 'VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof Department;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if(!$subject instanceof Department){
            throw new \Exception('You Dont have access to this');
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return $token->getUser()->getCompany() == $subject->getCompanyId();
        }

        return false;
    }
}
