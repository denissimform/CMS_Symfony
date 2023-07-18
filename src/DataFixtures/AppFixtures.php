<?php

namespace App\DataFixtures;

use App\Factory\DepartmentFactory;
use App\Factory\SkillsFactory;
use App\Factory\SubscriptionFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Factory\UserFactory;
use App\Factory\CompanyFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        SkillsFactory::createMany(40);
        DepartmentFactory::createMany(40);
        CompanyFactory::createMany(30);
        UserFactory::createMany(20);
        $manager->flush();
    }
}
