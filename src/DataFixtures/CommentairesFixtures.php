<?php

namespace App\DataFixtures;

use App\Entity\Commentaires;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentairesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 20; $i++) {
            $commentaire = new Commentaires();
            $commentaire->setText($faker->text(200))
                        ->setCreatedAt($faker->dateTimeBetween('-1 years', 'now'))
                        ->setUser($this->getReference('user_' . rand(1, 10)))
                        ->setProjets($this->getReference('projet_' . rand(1, 10)));

            $manager->persist($commentaire);
        }

        $manager->flush();
    }
}
