<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use App\Factory\CompanyFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        CompanyFactory::createMany(30);
        UserFactory::createMany(20);
        $manager->flush();
    }
}
