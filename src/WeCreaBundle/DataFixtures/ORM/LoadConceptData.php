<?php

namespace WeCreaBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use WeCreaBundle\Entity\Concept;
use WeCreaBundle\Entity\Images;

class LoadConceptData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $img = new Images();
        $img->setUrl('https://vignette1.wikia.nocookie.net/scifiminibuilders/images/8/88/Your_Picture_Here.png/revision/latest?cb=20130507015051');
        $img->setAlt('your picture name for referencing');

        $conceptPage = new Concept();
        $conceptPage->setTitle('Titre de la section');
        $conceptPage->setContent('Contenu de la section');
        $conceptPage->setImage($img);

        $manager->persist($conceptPage);
        $manager->flush();
    }
}