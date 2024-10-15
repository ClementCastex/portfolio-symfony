<?php

namespace App\DataFixtures;

use App\Entity\Projets;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProjetsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 10; $i++) {
            $projet = new Projets();
            $projet->setTitle($faker->sentence)
                   ->setDescription($faker->paragraph)
                   ->setImage('default.png')
                   ->setCategory($this->getReference('category_' . rand(0, 4)))
                   ->setUser($this->getReference('user_' . rand(1, 10)));

            $manager->persist($projet);

            // Sauvegarde une référence pour les commentaires
            $this->addReference('projet_' . $i, $projet);
        }

        $manager->flush();
    }
}
