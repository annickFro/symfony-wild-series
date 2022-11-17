<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create();

        for ($j=0; $j < 5; $j++) { 
            for ($i=1; $i < 6; $i++) { 
                $season = new Season();
                $season->setDescription($faker->text());
                $season->setNumber($i);
                $season->setYear(2010 + $i);
                $season->setProgram($this->getReference('program_' . $j));
                $manager->persist($season);
                $this->setReference('program_' . $j . '_season_' . $i, $season);
            }
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
