<?php

namespace App\DataFixtures;

use App\Entity\Event;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class EventFixtures extends BaseFixture implements DependentFixtureInterface
{
    public function loadData()
    {
        $this->createMany(100, 'event', function(){
            $event = (new Event())
                    ->setNom(mb_substr($this->faker->catchPhrase, 0, 50))
                    ->setDescription($this->faker->realText())
                    ->setDate($this->faker->dateTimeBetween('-3 years'))
                    ->setLieu($this->faker->address)
                    ->setPrice($this->faker->randomNumber(2))
                    ->setUser($this->getRandomReference('user_user'));

            $var= $this->faker->numberBetween(5, 18);

             for ($i=0; $i <$var ; $i++) {
               $event ->addUser($this->getRandomReference('user_user'));
            }

            return $event;
        });


    }



    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}




