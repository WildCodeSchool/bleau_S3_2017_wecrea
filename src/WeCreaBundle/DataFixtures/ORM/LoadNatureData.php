<?php

namespace WeCreaBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use WeCreaBundle\Entity\Nature;

class LoadNatureData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $natures = array(
            "Dessin",
            "Photographie",
            "Peinture",
            "Verre",
            "Sculture",
            "Artisanat d'art",
            "Céramique",
        );

        foreach ($natures as $n){
            $nature = new Nature();
            $nature->setName($n);
            $manager->persist($nature);
        }
        $manager->flush();
    }
}