<?php

namespace App\DataFixtures;

use App\Factory\DepartmentFactory;
use App\Factory\SkillsFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Factory\UserFactory;
use App\Factory\CompanyFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        SkillsFactory::createMany(40);
        DepartmentFactory::createMany(40);
        CompanyFactory::createMany(30);
        UserFactory::createMany(20);
        $manager->flush();
    }
}
