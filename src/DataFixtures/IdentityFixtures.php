<?php

namespace App\DataFixtures;

use App\Entity\Identity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class IdentityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 10; $i++) {
            $identity = new Identity();
            $identity->setName($faker->firstName)
                    ->setSurname($faker->lastName)
                    ->setEmail($faker->email)
                    ->setAddress($faker->address);

            $manager->persist($identity);

            $this->addReference('identity_' . $i, $identity);
        }

        $manager->flush();
    }
}
