<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use app\Entity\Actor;
use app\Entity\Program;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($iActor=0 ; $iActor<10 ; $iActor++) {
            $actor = new Actor();  
            $actor->setName($faker->firstName() . ' ' . strtoupper($faker->lastName()));  

            // for each actor insert 3 random programs
            for ($iProg=0; $iProg < 3; $iProg++) { 
                $actor->addProgram($this->getReference('program_' . rand(0,4))) ;
            }
            $manager->persist($actor);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        //  on retourne les classes de fixtures dont cette classe dépend
        return [
            ProgramFixtures::class,
        ] ;

    }
}
