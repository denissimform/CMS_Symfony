<?php

namespace App\Service;

use App\Entity\CompanySubscription;
use App\Repository\CompanySubscriptionRepository;

class UserSubscriptionChecker
{
    public function __construct(
        private CompanySubscriptionRepository $csr
    ) {
    }

    public function isAlreadySubscribed(int $company): bool
    {
        $isSubscribed = $this->csr->findOneBy([
            'company' => $company,
            'status' => CompanySubscription::PLAN_STATUS['CURRENT']
        ]);

        if ($isSubscribed)
            return true;

        return false;
    }
}
