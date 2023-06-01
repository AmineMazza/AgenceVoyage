<?php

namespace App\DataFixtures;

use App\Entity\Agent;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $hasher)
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail("admin@gmail.com");
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setPassword($this->hasher->hashPassword($user,"admin"));
        $manager->persist($user);
        
        $userAgent = new User();
        $userAgent->setEmail("agent@gmail.com");
        $userAgent->setRoles(["ROLE_AGENT"]);
        $userAgent->setPassword($this->hasher->hashPassword($userAgent,"agent"));
        $manager->persist($user);
        $agent = new Agent();
        $agent->setAgence("Agence1");
        $agent->setNom("agentNom");
        $agent->setPrenom("agentPre");
        $agent->setTelephoneMobile("0743216532");
        $agent->setAdresse("433 lot tfkt");
        $agent->setAbonnement("premium");
        $agent->setBstatus(true);
        $agent->setIdUser($userAgent);

        $manager->persist($agent);

        $manager->flush();
    }
}
