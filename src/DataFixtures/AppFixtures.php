<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Factory\CompanyFactory;
use Doctrine\Persistence\ObjectManager;
use App\Factory\CompanySubscriptionFactory;
use App\Factory\SubscriptionFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;

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
        SubscriptionFactory::createMany(6);
        // CompanyFactory::createMany(10);
        // UserFactory::createMany(10);
        // UserFactory::createOne(
        //     [
        //         'roles' => ['ROLE_ADMIN'],
        //         'username' => 'admin',
        //         'password' => 'admin',
        //         'email' => 'admin',
        //     ]
        // );

        $manager->flush();
    }
}
