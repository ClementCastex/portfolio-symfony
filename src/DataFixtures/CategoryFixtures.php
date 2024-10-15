<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = ['Web', 'Mobile', 'Data Science', 'DevOps', 'Design'];

        foreach ($categories as $key => $name) {
            $category = new Category();
            $category->setName($name);
            $manager->persist($category);

            // Sauvegarde une référence pour les autres fixtures
            $this->addReference('category_' . $key, $category);
        }

        $manager->flush();
    }
}
