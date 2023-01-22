<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $category = new Categories();
        $category ->setName('Yoga');
        $category->setSlug('yoga');
        $manager->persist($category);

        $manager->flush();
    }
}
