<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Repository\CompanySubscriptionRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class FeatureAccessVoter extends Voter
{
    public const FEATURE_ACCESS = 'FEATURE_ACCESS';

    public function __construct(
        private CompanySubscriptionRepository $csr
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::FEATURE_ACCESS]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // check if the user has already purchased any subscription or not
        switch ($attribute) {
            case self::FEATURE_ACCESS:
                if ($this->csr->findOneBy(['company' => $user->getCompany()->getId()]))
                    return true;
        }

        return false;
    }
}
