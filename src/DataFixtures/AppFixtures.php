<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory; 
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker; 

    public function __construct( private UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->faker = Factory::create('fr_FR'); 
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail("a@a.fr");
        $user->setPassword($this->userPasswordHasher->hashPassword($user, '123'));
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        
        for ($i = 0; $i < 50; $i++) {
            $ingredient = new Ingredient(); 
            $ingredient->setName($this->faker->word()) 
                       ->setPrice(mt_rand(1, 100)) 
                       ->setCreatedAt(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-6 months', 'now'))); 

            $manager->persist($ingredient); 
        }

        $manager->flush(); 
    }
}