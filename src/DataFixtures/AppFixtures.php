<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use App\Entity\Partenaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    private $encoder;
    
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $roles1 = new Role();
        $roles1->setLibelle("ROLE_SUP_ADMIN");
        $manager->persist($roles1); 

        $roles2 = new Role();
        $roles2->setLibelle("ROLE_ADMIN");
        $manager->persist($roles2);
        
        $roles3 = new Role();
        $roles3->setLibelle("ROLE_CAISSIER");
        $manager->persist($roles3);

        $part = new Partenaire();
        $part->setNinea("ner124");
        $part->setRmc("ner124");
        $part->setTel("77325481");
        $part->setLogo("ner124");
        $manager->persist($part);

        $user1 = new User();
        $user1->setUsername("sisco");
        $user1->setPassword($this->encoder->encodePassword($user1, "12345"));
       
        $user1->setPrenom("francisco");
        $user1->setNom("lopez");
        $user1->setEmail("flammedamour2017@gmail.com");
        $user1->setImage("avatar");
        $user1->setIsActive(true);
        $user1->setRole($roles1);
        $user1->setPart($part);
        
        $manager->persist($user1);

        $manager->flush();
    }
}
