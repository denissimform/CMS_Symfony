<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\CompanyFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);


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
        CompanyFactory::createMany(50);

        $manager->flush();
    }
}
