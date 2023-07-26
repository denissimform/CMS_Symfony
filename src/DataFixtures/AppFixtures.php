<?php

namespace App\DataFixtures;

use App\Entity\CompanySubscription;
use App\Entity\User;
use App\Factory\CompanyFactory;
use App\Factory\CompanySubscriptionFactory;
use App\Factory\DepartmentFactory;
use App\Factory\SubscriptionDurationFactory;
use App\Factory\SubscriptionFactory;
use App\Factory\TransactionFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        CompanyFactory::createMany(20);
        UserFactory::createOne([
            'email' => 'superadmin@gmail.com',
            'roles' => ['ROLE_SUPER_ADMIN']
        ]);
        UserFactory::createMany(2, [
            'roles' => ['ROLE_ADMIN']
        ]);
        UserFactory::createMany(3, [
            'roles' => ['ROLE_BDA']
        ]);
        UserFactory::createMany(10);

        SubscriptionFactory::createMany(6);
        TransactionFactory::createMany(10);
        SubscriptionFactory::createMany(3);
        SubscriptionDurationFactory::createMany(6);
        CompanySubscriptionFactory::createMany(10);

        DepartmentFactory::createMany(10);

        $manager->flush();
    }
}
