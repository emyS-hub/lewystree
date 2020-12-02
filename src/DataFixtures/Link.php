<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
//use App\Entity\Link;

class Link extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /*for ($i = 1; $i <= 10; $i++) {
            $link = new Link();
            $link->setIcon()
            ->setTitle()
            ->setUrl();
        }*/

        $manager->flush();
    }
}
