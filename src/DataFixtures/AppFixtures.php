<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Invoice;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * Encodeur de mots de passe
     * 
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for($i=0; $i<10; $i++){
            $user = new User();
            $chrono = 1;

            $hasher = $this->passwordHasher->hashPassword($user, "password");
            $user->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setEmail($faker->email)
                ->setPassword($hasher);
            $manager->persist($user);
            
            for($j=0; $j<mt_rand(5,20); $j++){
                $customer = new Customer();
                $customer->setFirstname($faker->firstName)
                    ->setLastname($faker->lastName)
                    ->setEmail($faker->email)
                    ->setCompany($faker->company)
                    ->setUser($user);
                $manager->persist($customer);

                for($k=0; $k<mt_rand(3,10); $k++){
                    $invoice = new Invoice();
                    $invoice->setAmount($faker->randomFloat(2,250,5000))
                        ->setSentAt($faker->dateTimeBetween('-6 months'))
                        ->setStatus($faker->randomElement(['SENT','PAID','CANCELLED']))
                        ->setChrono($chrono)
                        ->setCustomer($customer);
                    $chrono++;
                    $manager->persist($invoice);
                }
            }
        }

        $manager->flush();
    }
}
