<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\User;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {

    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);


        $faker = Factory::create('en_US'); // polskie dane

        for ($i = 0; $i < 3; $i++) {

            $user = new User();

            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->lastName());
            $user->setEmail($faker->unique()->safeEmail());

            // (!) ONLY TEMPORARY - safer method above - need to modify User Entity Class
            /*$user->setPassword(
                password_hash('12345', PASSWORD_DEFAULT)
            );*/
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, '12345')
            );            

            /*$user->setCreatedAt(
                \DateTimeImmutable::createFromMutable(
                    $faker->dateTimeBetween('-1 year', 'now')
                )
            );*/
            $user->setCreatedAt(new \DateTimeImmutable());

            $user->setIsApproved($faker->boolean(80)); // 80% true, 20% false

            $manager->persist($user);
        }

        $manager->flush();        
    }
}
