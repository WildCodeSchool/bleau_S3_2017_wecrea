<?php

namespace WeCreaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use WeCreaBundle\Entity\Artist;
use WeCreaBundle\Entity\Carrousel;
use WeCreaBundle\Entity\Images;
use WeCreaBundle\Entity\Work;
use WeCreaBundle\Entity\Nature;
use WeCreaBundle\Form\WorkType;
use WeCreaBundle\Repository\NatureRepository;
use WeCreaBundle\WeCreaBundle;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdminController extends Controller
{
    public function newArtistWorkAction(Request $request)
    {
        $artist = new Artist();
        $image = new Images();
        $work = new Work();

        $formArtist = $this->createForm('WeCreaBundle\Form\ArtistType', $artist);
        $formImageArtist = $this->createForm('WeCreaBundle\Form\ImagesType', $image);
        $formWorkImage = $this->createForm('WeCreaBundle\Form\ImagesType', $image);
        $formWork = $this->createForm('WeCreaBundle\Form\WorkType', $work);

        return $this->render('@WeCrea/Admin/artist_work_creation.html.twig', array(
            'artist' => $artist,
            'form' => $formArtist->createView(),
            'formImage' => $formImageArtist->createView(),
            'formWorkImage' => $formWorkImage->createView(),
            'formWork' => $formWork->createView(),
        ));
    }

    public function editArtistWorkAction($id)
    {
        $image = new Images();
        $work = new Work();

        $em = $this->getDoctrine()->getManager();
        $artist = $em->getRepository('WeCreaBundle:Artist')->findOneById($id);
        $works = $em->getRepository('WeCreaBundle:Work')->findByArtist(array(
            'artist' => $artist
        ));

        foreach($works as $key){
            $editWorkForms[] = $this->createForm('WeCreaBundle\Form\WorkType', $key)->createView();
        }

        $editArtistForm = $this->createForm('WeCreaBundle\Form\ArtistType', $artist);
        $artistImageForm = $this->createForm('WeCreaBundle\Form\ImagesType', $image);
        $workImageForm = $this->createForm('WeCreaBundle\Form\ImagesType', $image);
        $workForm = $this->createForm('WeCreaBundle\Form\WorkType', $work);

        return $this->render('@WeCrea/Admin/artist_work_edition.html.twig', array(
            'artist' => $artist,
            'works' => $works,
            'editArtistForm' => $editArtistForm->createView(),
            'artistImageForm' => $artistImageForm->createView(),
            'workForm' => $workForm->createView(),
            'editWorkForms' => $editWorkForms,
            'workImageForm' => $workImageForm->createView(),
        ));
    }

    public function newArtistImageAjaxAction(Request $request)
    {
        $image = new Images();

        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $alt = $request->request->get('wecreabundle_images')['alt'];
            $file = $request->files->get('wecreabundle_images')['url'];

            $fileName = uniqId() . '.' . $file->guessExtension();
            $file->move($this->getParameter('image_directory'), $fileName);
            dump($request);
            $image->setUrl($fileName);
            $image->setAlt($alt);

            /* As the artist profile has been created
            *  before the submission of the new image,
            *  we can make the association
            */
            $idArt = $request->request->get('idArt');
            $artist = $em->getRepository('WeCreaBundle:Artist')->findOneById($idArt);
            $artist->addImage($image);

            $em->persist($image);
            $em->flush();

            /* We send back the data regarding the freshly
            *  created image to enable deletion
            */
            $image = $em->getRepository('WeCreaBundle:Images')->findOneByUrl($fileName);

            $encoders = array(new JsonEncoder());
            $normalizer = array(new ObjectNormalizer());
            $serializer = new Serializer($normalizer, $encoders);

            $jsonImage = $serializer->serialize($image, "json");

            $response = new Response($jsonImage);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    public function newArtistProfilAjaxAction(Request $request){

        $artist = new Artist();

        if($request->isXmlHttpRequest()){
            $em = $this->getDoctrine()->getManager();

            $name = $request->request->get('wecreabundle_artist')['name'];
            $firstName = $request->request->get('wecreabundle_artist')['firstname'];
            $localization = $request->request->get('wecreabundle_artist')['localization'];
            $expertise = $request->request->get('wecreabundle_artist')['expertise'];

            $artist->setName($name);
            $artist->setFirstname($firstName);
            $artist->setLocalization($localization);
            $artist->setExpertise($expertise);

            $em->persist($artist);
            $em->flush();

            /* We send back the data regarding the profile */
            $artist = $em->getRepository('WeCreaBundle:Artist')->findAll();
            /* Let's return the latest profile created */
            $artist = $artist[count($artist)-1];

            $encoders = array(new JsonEncoder());
            $normalizer = array(new ObjectNormalizer());
            $serializer = new Serializer($normalizer, $encoders);

            $jsonArtist = $serializer->serialize($artist, "json");

            $response = new Response($jsonArtist);
            $response->headers->set('Content-Type','application/json');

            return $response;
        }
    }

    public function deleteArtistImageAjaxAction(Request $request){

        $em = $this->getDoctrine()->getManager();

        /* If the artist profile has been created */
        if(!empty($request->request->get('idArt'))){
            $idArt = $request->request->get('idArt');
            $artist = $em->getRepository('WeCreaBundle:Artist')->findOneById($idArt);
        }
        else{
            $artist = false;
        }

        /* Array of images = 1 or all images (if window closed for example) */
        $idImg = $request->request->get('idImg');

        for($i=0; $i<count($idImg); ++$i){
            $image = $em->getRepository('WeCreaBundle:Images')->findOneById($idImg[$i]);
            $url = $image->getUrl();

            /* If the artist profile exists, let's remove the images it
            * is linked with
            */
            if($artist !== false){
                $artist->removeImage($image);
            }

            $path = $this->getParameter('image_directory')."/".$url;

            if(file_exists($path)){
                unlink($path);
            }
            $em->remove($image);
        }

        $em->flush();

        return new Response("L'image a bien été supprimée");
    }

    public function deleteWorkImageAjaxAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $idWork = $request->request->get('idWork');
        $work = $em->getRepository('WeCreaBundle:Work')->findOneById($idWork);

        $idImg = $request->request->get('idImg');

        $image = $em->getRepository('WeCreaBundle:Images')->findOneById($idImg);
        $url = $image->getUrl();

        /* Let's remove the images the work is linked with */

        $work->removeImage($image);

        $path = $this->getParameter('image_directory')."/".$url;

        if(file_exists($path)){
            unlink($path);
        }
        $em->remove($image);

        $em->flush();

        return new Response("L'image a bien été supprimée");
    }

    public function newWorkImageAjaxAction(Request $request)
    {
        $image = new Images();
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $alt = $request->request->get('wecreabundle_images')['alt'];
            $file = $request->files->get('wecreabundle_images')['url'];

            $fileName = uniqId() . '.' . $file->guessExtension();
            $file->move($this->getParameter('image_directory'), $fileName);

            $image->setUrl($fileName);
            $image->setAlt($alt);

            if(!empty($request->request->get('idWork'))){
                $idWork = $request->request->get('idWork');
                $work = $em->getRepository('WeCreaBundle:Work')->findOneById($idWork);
                $work->addImage($image);
            }

            $em->persist($image);
            $em->flush();

            /* We send back the data regarding the freshly
            *  created image to enable modifications
            */
            $image = $em->getRepository('WeCreaBundle:Images')->findOneByUrl($fileName);

            $encoders = array(new JsonEncoder());
            $normalizer = array(new ObjectNormalizer());
            $serializer = new Serializer($normalizer, $encoders);

            $jsonImage = $serializer->serialize($image, "json");

            $response = new Response($jsonImage);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    public function newWorkCaracteristicsAjaxAction(Request $request){

        $work = new Work();

        if($request->isXmlHttpRequest()){
            $em = $this->getDoctrine()->getManager();

            $title = $request->request->get('wecreabundle_work')['title'];
            $technic = $request->request->get('wecreabundle_work')['technic'];
            $dimensions = $request->request->get('wecreabundle_work')['dimensions'];
            $weight = $request->request->get('wecreabundle_work')['weight'];
            $quantity = $request->request->get('wecreabundle_work')['quantity'];
            $timelimit = $request->request->get('wecreabundle_work')['timelimit'];
            $type = $request->request->get('wecreabundle_work')['nature']['name'];
            $price = $request->request->get('wecreabundle_work')['price'];

            /* let's check if new nature or not. If 0, yes... */
            $nature = $em->getRepository('WeCreaBundle:Nature')->findOneByName($type);
            if(count($nature) == 0){
                $nature = new Nature();
                $nature->setName($type);
                $work->setNature($nature);
            }
            else{
                $work->setNature($nature);
            }

            if(!empty($request->request->get('idArt'))){
                $idArt = $request->request->get('idArt');
                $artist = $em->getRepository('WeCreaBundle:Artist')->findOneById($idArt);
                $work->setArtist($artist);
            }

            $work->setTitle($title);
            $work->setTechnic($technic);
            $work->setDimensions($dimensions);
            $work->setWeight($weight);
            $work->setQuantity($quantity);
            $work->setTimelimit($timelimit);
            $work->setPrice($price);

            $em->persist($work);
            $em->persist($nature);
            $em->flush();

            /* We send back the data regarding the profile */
            $work = $em->getRepository('WeCreaBundle:Work')->findAll();
            dump($work);
            /* Let's get the last work created */
            $work = $work[count($work)-1];


            $encoders = new JsonEncoder();
            $normalizer = new ObjectNormalizer();
            $normalizer->setIgnoredAttributes(array("artist", "nature"));
            $serializer = new Serializer(array($normalizer), array($encoders));

            $jsonWork = $serializer->serialize($work, "json");

            $response = new Response($jsonWork);
            $response->headers->set('Content-Type','application/json');

            return $response;
        }
    }

    public function deleteWorkAjaxAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $idWork = $request->request->get('idWork');

        $work = $em->getRepository('WeCreaBundle:Work')->findOneById($idWork);
        $images = $work->getImages();

        if(isset($images) && !empty($images)){
            for($i=0; $i<count($images); ++$i) {
                $idImg = $images[$i]->getId();
                $image = $em->getRepository('WeCreaBundle:Images')->findOneById($idImg);
                $url = $image->getUrl();

                $path = $this->getParameter('image_directory')."/".$url;

                if(file_exists($path)){
                    unlink($path);
                }
                $em->remove($image);
            }
        }

        $em->remove($work);
        $em->flush();

        return new Response("L'oeuvre et ses images ont bien été supprimées");
    }

    public function editCarrouselAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $carrousels = $em->getRepository('WeCreaBundle:Carrousel')->findAll();

        $carrousel = new Carrousel();
        $image = new Images();
        $formCarrousel = $this->createForm('WeCreaBundle\Form\CarrouselType', $carrousel);
        $formImages = $this->createForm('WeCreaBundle\Form\ImagesType', $image);

        return $this->render('@WeCrea/Admin/carrousel_edition.html.twig', array(
            'formCarrousel' => $formCarrousel->createView(),
            'formImages' => $formImages->createView(),
            'carrousels' => $carrousels,
            'image' => $image,
        ));
    }

    public function addImageCarrouselAction(Request $request) {
        $image = new Images();

        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();

            //crea
            $file = $request->files->get('wecreabundle_images')['url'];
            $alt = $request->request->get('wecreabundle_images')['alt'];

            $fileName = uniqId() . '.' . $file->guessExtension();

            $file->move($this->getParameter('image_directory'), $fileName);

            $image->setUrl($fileName);
            $image->setAlt($alt);

            $em->persist($image);
            $em->flush();

            //recup
            $image = $em->getRepository('WeCreaBundle:Images')->findOneByUrl($fileName);

            $encoders = array(new JsonEncoder()) ;
            $normalizer = array(new ObjectNormalizer()) ;
            $serializer = new Serializer($normalizer, $encoders);

            $jsonImage = $serializer->serialize($image, 'json');

            $response = new response($jsonImage);

            $response->headers->set('Content-Type', 'application/json');


            return $response;
        }
    }

    public function addCarrouselAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $carrousel = new Carrousel();

        if($request->isXmlHttpRequest()) {
            $content = $request->request->get('wecreabundle_carrousel')['content'];
            $title = $request->request->get('wecreabundle_carrousel')['title'];
            $rout = $request->request->get('wecreabundle_carrousel')['rout'];
            $image = $request->request->get('images_id');

            $image = $em->getRepository('WeCreaBundle:Images')->findOneById($image);

            $carrousel->setContent($content);
            $carrousel->setTitle($title);
            $carrousel->setRout($rout);
            $carrousel->setImages($image);

            $em->persist($carrousel);
            $em->flush();

            $carrousels = $em->getRepository('WeCreaBundle:Carrousel')->findAll();

            $carrousel = $carrousels[count($carrousels)-1];

            $encoders = array(new JsonEncoder()) ;
            $normalizer = array(new ObjectNormalizer()) ;
            $serializer = new Serializer($normalizer, $encoders);

            $jsonCarrousel = $serializer->serialize($carrousel, 'json');

            $response = new response($jsonCarrousel);

            $response->headers->set('Content-Type', 'application/json');


            return $response;
        }
    }

    public function deleteCarrouselAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $id = $request->query->get('id');

        $carrousel = $em->getRepository('WeCreaBundle:Carrousel')->findOneById($id);

        $idImage = $carrousel->getImages()->getId();
        $image = $em->getRepository('WeCreaBundle:Images')->findOneById($idImage);
        $url = $image->getUrl();

        $path = $this->getParameter('image_directory')."/".$url;

        unlink($path);

        $em->remove($carrousel);
        $em->remove($image);

        $em->flush();

        return new response('La vignette a bien été supprimer');
    }
}