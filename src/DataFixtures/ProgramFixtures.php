<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{

    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR') ;



        for ($prog=0; $prog < 5 ; $prog++) { 
            $randomCategory = CategoryFixtures::CATEGORIES[rand(0, count(CategoryFixtures::CATEGORIES) - 1)];
            $program = new Program();  
            $program->setTitle('(tit) ' . $faker->sentence()); 

            $slug = $this->slugger->slug($program->getTitle()) ;
            $program->setSlug($slug);

            $program->setSynopsis('(syn) ' .$faker->text());  
            $program->setPoster('(pos) ' .$faker->text());  
            $program->setCategory($this->getReference('category_' . $randomCategory)) ;
            $manager->persist($program);
            $this->addReference('program_' . $prog, $program);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        //  on retounr les classes de fixtures dont cette classe dépend
        return [
            CategoryFixtures::class,
            // ActorFixtures::class,
        ] ;

    }
}
