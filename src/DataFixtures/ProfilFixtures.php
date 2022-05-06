<?php

namespace App\DataFixtures;

use App\Entity\Profil;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProfilFixtures extends Fixture
{
    public const ADMIN_REFERENCE = "ADMIN";
    public const COM_REFERENCE  = "COMMERCIAL";

    public function load(ObjectManager $manager)
    {
        $profils =["ADMIN","COMMERCIAL"];

        foreach ($profils as $key => $libelle) {
            $profil = new Profil();
            $profil->setLibelle($libelle);
            $manager->persist($profil);

            if($libelle == "ADMIN"){
                $this->addReference(self::ADMIN_REFERENCE,$profil);
            }elseif ($libelle == "COMMERCIAL"){
                $this->addReference(self::COM_REFERENCE,$profil);
            }

            $manager->flush();
        }   
    }
}
