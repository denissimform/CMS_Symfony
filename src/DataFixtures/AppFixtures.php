<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\CompanyFactory;
use App\Factory\SubscriptionFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
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
