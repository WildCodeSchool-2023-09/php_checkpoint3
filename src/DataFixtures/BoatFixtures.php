<?php

namespace App\DataFixtures;
use App\Entity\Boat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BoatFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $boat = new Boat();
        $boat->setName('North'); // or addReference??
        $boat->setCoordX(0);
        $boat->setCoordY(-1);
        $manager->persist($boat);

        $boat = new Boat();
        $boat->setName('South'); // or addReference??
        $boat->setCoordX(0);
        $boat->setCoordY(1);
        $manager->persist($boat);

        $boat = new Boat();
        $boat->setName('East'); // or addReference??
        $boat->setCoordX(1);
        $boat->setCoordY(0);
        $manager->persist($boat);

        $boat = new Boat();
        $boat->setName('West'); // or addReference??
        $boat->setCoordX(-1);
        $boat->setCoordY(0);
        $manager->persist($boat);

        $manager->flush();
    }
}



