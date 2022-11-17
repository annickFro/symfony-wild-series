<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Episode;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($j=0; $j < 5; $j++) { 
            for ($k=1; $k < 6; $k++) { 
                for ($i=1; $i < 21; $i++) { 
                    $episode=new Episode();
                    $episode->setNumber($i);
                    $episode->setSynopsis($faker->text());
                    $episode->setTitle($faker->text());
                    $episode->setSeason($this->getReference('program_' . $j . '_season_' . $k));
                    $manager->persist($episode);
                }
            }
        }

        $manager->flush();
    }
    
    public function getDependencies()
    {
        //  on retounr les classes de fixtures dont cette classe dépend
        return [
            ProgramFixtures::class,
            SeasonFixtures::class,
        ] ;

    }
}
