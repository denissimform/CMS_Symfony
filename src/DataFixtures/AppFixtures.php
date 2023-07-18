<?php

namespace App\DataFixtures;

use App\Entity\Subscription;
use App\Factory\CompanyFactory;
use App\Factory\CompanySubscriptionFactory;
use App\Factory\SubscriptionFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        CompanyFactory::createMany(10);
        SubscriptionFactory::createMany(3);

        CompanySubscriptionFactory::createMany(20, function () {
            return [
                "companyId" => CompanyFactory::random(),
                "subscriptionId" => SubscriptionFactory::random()
            ];
        });

        $manager->flush();
    }
}
