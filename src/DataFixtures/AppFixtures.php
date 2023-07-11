<?php

namespace App\DataFixtures;

use App\Factory\CompanyFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        CompanyFactory::createMany(20);

        $manager->flush();
    }
}
