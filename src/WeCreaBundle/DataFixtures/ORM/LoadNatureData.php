<?php

namespace WeCreaBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use WeCreaBundle\Entity\Images;
use WeCreaBundle\Entity\Legal;
use WeCreaBundle\Entity\Nature;

/**
 * Class LoadNatureData
 * @package WeCreaBundle\DataFixtures\ORM
 */
class LoadNatureData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $natures = array(
            "Peinture",
            "Sculpture",
            "Dessin",
            "Photographie",
            "Artisanat d'art",
            "Curiosités"
        );


        for ($i = 0 ; $i < count($natures); $i++)
        {
            copy(__DIR__ . '/../../Resources/public/pics/no_picture.png', __DIR__ . '/../../../../web/images/picture_template_' . $i . '.jpg');

            $picture = new Images();
            $picture->setUrl('picture_template_' . $i . '.jpg');
            $picture->setAlt('picture');

            $nature = new Nature();

            $nature->setImages($picture);
            $nature->setName($natures[$i]);
            $nature->setFontColor('#000');

            $manager->persist($nature);
        }

        $legal = new Legal();
        $legal->setTva('20');
        $legal->setMention('WE-CREA SARL, au capital de 10 000 €, entreprise immatriculée au RCS de Sedan sous le
            numéro 799146493, dont le siège social est situé au 8, rue Basse, 08190 ST
            GERMAINMONT');
        $legal->setDefiscalisation('Your text here');
        $legal->setCgu('Your CGU');
        $legal->setCgv('Your CGV');
        $legal->setReturnWorkText('plop');
        $manager->persist($legal);

        $manager->flush();

    }
}