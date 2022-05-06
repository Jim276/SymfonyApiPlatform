<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Invoice;
use App\Entity\Customer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture implements DependentFixtureInterface
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
        $profils = ['ADMIN', 'COMMERCIAL'];
        $chrono = 1;
        
        foreach ($profils as $key) { 
            
    
        for ($u=0; $u < 10 ; $u++) 
        { 
            if($key =='ADMIN'){
                $user = new User();
                $user->setProfil($this->getReference(ProfilFixtures::ADMIN_REFERENCE));
                $manager->persist($user);
                
            }elseif($key =='COMMERCIAL') {
                $user = new User();
                $user->setProfil($this->getReference(ProfilFixtures::COM_REFERENCE));
                $manager->persist($user);
            }

            $hasher = $this->passwordHasher->hashPassword($user, "password");
            $user->setFirstname($faker->firstName)
                 ->setLastname($faker->lastName)
                 ->setEmail($faker->email)
                 ->setPassword($hasher);
            $manager->persist($user);

            for ($c=0; $c < mt_rand(5, 20) ; $c++) 
        { 
            $customer = new Customer();
            $customer->setFirstname($faker->firstName)
                    ->setLastname($faker->lastName)
                    ->setEmail($faker->email)
                    ->setCompany($faker->company)
                    ->setUser($user);
            $manager->persist($customer);

            for ($i=0; $i < mt_rand(3, 10) ; $i++) { 
                $invoice = new Invoice();
                $invoice->setAmount($faker->randomFloat(2, 250, 5000))
                        ->setSentAt($faker->dateTimeBetween('-6 months'))
                        ->setStatus($faker->randomElement(['SENT','PAID', 'CANCELLED']))
                        ->setCustomer($customer)
                        ->setChrono($chrono);
                $chrono++;

                $manager->persist($invoice);
            }

        }
        }
    

        

        $manager->flush();
        }
    }

    public function getDependencies()
    {
        return array(
            ProfilFixtures::class,
        );
    }
}