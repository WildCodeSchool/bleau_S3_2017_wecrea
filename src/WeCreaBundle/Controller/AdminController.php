<?php

namespace WeCreaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use WeCreaBundle\Entity\Actu;
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
    /* Render the page for listing all the artists */
    public function artistListAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $artists = $em->getRepository('WeCreaBundle:Artist')->findAll();

        return $this->render('@WeCrea/Admin/artist_list.html.twig', array(
            'artists' => $artists
        ));
    }

    /* Method for deleting the artist */
    public function deleteArtistAction($id){
        $em = $this->getDoctrine()->getManager();
        $artist = $em->getRepository('WeCreaBundle:Artist')->findOneById($id);

        $works = $em->getRepository('WeCreaBundle:Work')->findByArtist(array(
            'artist' => $artist
        ));

        foreach($works as $work){
            $images = $work->getImages();
            foreach($images as $image){
                $img = $image->getUrl();
                $file = $this->getParameter('image_directory')."/".$img;

                if(file_exists($file)){
                    unlink($file);
                }
            }
            $em->remove($work);
        }

        $em->remove($artist);
        $em->flush();

        return $this->redirectToRoute('we_crea_admin_artist_list');

    }

    /* Render the page for the creation of a new artist & some or all of his/her works */
    public function newArtistWorkAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $artist = new Artist();
        $image = new Images();
        $work = new Work();

        $formArtist = $this->createForm('WeCreaBundle\Form\ArtistType', $artist);
        $formImageArtist = $this->createForm('WeCreaBundle\Form\ImagesType', $image);
        $formWorkImage = $this->createForm('WeCreaBundle\Form\ImagesWorkType', $image);
        $formLastWorkImages = $this->createForm('WeCreaBundle\Form\LastImagesWorkType', $image);
        $formWork = $this->createForm('WeCreaBundle\Form\WorkType', $work);

        if($request->isXmlHttpRequest()) {
            $newArtist = $request->request->get('wecreabundle_artist');
            $newWork = $request->request->get('wecreabundle_work');
            $newArtistImage = $request->request->get('wecreabundle_images');
            $newWorkImage = $request->request->get('wecreabundle_images_work');
            $lastWorkImage = $request->request->get('wecreabundle_images_last_work_images');

            $lastOrNew = isset($newWorkImage) || isset($lastWorkImage) ?
                isset($newWorkImage) ? $lastOrNew = 'new' : $lastOrNew = 'last' :
                    $lastOrNew = NULL;

            if(isset($newWork)) {
                $formWork->handleRequest($request);

                $natureId = $request->request->get('wecreabundle_work')['nature'];
                $nature = $em->getRepository('WeCreaBundle:Nature')->findOneById($natureId);

                $work->setNature($nature);

                $idArt = $request->request->get('idArt');
                $artist = $em->getRepository('WeCreaBundle:Artist')->findOneById($idArt);
                $work->setArtist($artist);

                $em->persist($work);
                $em->flush();

                /* We send back the data regarding the profile */
                $work = $em->getRepository('WeCreaBundle:Work')->findAll();

                /* Let's get the last work created */
                $work = $work[count($work) - 1];

                $encoders = new JsonEncoder();
                $normalizer = new ObjectNormalizer();
                $normalizer->setIgnoredAttributes(array("artist", "nature"));
                $serializer = new Serializer(array($normalizer), array($encoders));

                $jsonWork = $serializer->serialize($work, "json");

                $response = new Response($jsonWork);
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }

            if($newArtist){
                $formArtist->handleRequest($request);

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

            if(isset($newArtistImage)) {
                $formImageArtist->handleRequest($request);
                $file = $request->files->get('wecreabundle_images')['url'];

                $fileName = uniqId() . '.' . $file->guessExtension();
                $file->move($this->getParameter('image_directory'), $fileName);
                $image->setUrl($fileName);

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

            if(isset($lastOrNew)) {

                if($lastOrNew == "new"){
                    $formWorkImage->handleRequest($request);
                    $file = $request->files->get('wecreabundle_images_work')['url'];
                }
                else if($lastOrNew == "last"){
                    $formLastWorkImages->handleRequest($request);
                    $file = $request->files->get('wecreabundle_images_last_work_images')['url'];
                }

                $fileName = uniqId() . '.' . $file->guessExtension();
                $file->move($this->getParameter('image_directory'), $fileName);

                $image->setUrl($fileName);

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

        return $this->render('@WeCrea/Admin/artist_work_creation.html.twig', array(
            'artist' => $artist,
            'form' => $formArtist->createView(),
            'formImage' => $formImageArtist->createView(),
            'formWorkImage' => $formWorkImage->createView(),
            'formWork' => $formWork->createView(),
        ));
    }

    /*
    * Method for deleting an image from the artist profile
    */

    public function deleteArtistImageAjaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /* If the artist profile has been created */
        $idArt = $request->request->get('idArt');
        $artist = $em->getRepository('WeCreaBundle:Artist')->findOneById($idArt);

        $idImg = $request->request->get('idImg');

        $image = $em->getRepository('WeCreaBundle:Images')->findOneById($idImg);
        $url = $image->getUrl();

        /*
        * Let's remove the image linked with the artist
        */
        $artist->removeImage($image);

        $path = $this->getParameter('image_directory')."/".$url;

        if(file_exists($path)){
            unlink($path);
        }

        $em->remove($image);
        $em->flush();

        return new Response("L'image a bien été supprimée");
    }

    /*
     * Method for deleting a specific image linked to a specific work
     */

    public function deleteWorkImageAjaxAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        /* Stores the work id */
        $idWork = $request->request->get('idWork');
        /* Looks for the work corresponding to the id */
        $work = $em->getRepository('WeCreaBundle:Work')->findOneById($idWork);
        /* Stores the image id */
        $idImg = $request->request->get('idImg');
        /* Looks for the image corresponding to the id */
        $image = $em->getRepository('WeCreaBundle:Images')->findOneById($idImg);
        /* Gets the url of the image */
        $url = $image->getUrl();
        /* Let's remove the images the work is linked with */
        $work->removeImage($image);
        /* Check if the image exists within the images folder */
        $path = $this->getParameter('image_directory')."/".$url;
        /* If yes, unlink the image */
        if(file_exists($path)){
            unlink($path);
        }
        /* Remove this image */
        $em->remove($image);
        /* Update the database */
        $em->flush();
        /* Confirm the image has been deleted successfully */
        return new Response("L'image a bien été supprimée");
    }

    /*
     * Method for deleting the work & all its images
     */

    public function deleteWorkAjaxAction(Request $request){
        dump($request);
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

        return new Response("L'oeuvre ".$work->getTitle()." et ses images ont bien été supprimées");
    }

    /*
     * Access to the page for editing a specific artist profile & his/her works
     */

    public function editArtistWorkAction($id, Request $request)
    {
        $image = new Images();
        $work = new Work();

        $em = $this->getDoctrine()->getManager();
        $artist = $em->getRepository('WeCreaBundle:Artist')->findOneById($id);
        $works = $em->getRepository('WeCreaBundle:Work')->findByArtist(array(
            'artist' => $artist
        ));

        if(!empty($work)) {
            foreach ($works as $key) {
                $editWorkForms[] = $this->createForm('WeCreaBundle\Form\WorkType', $key)->createView();
            }
        }

        $editArtistForm = $this->createForm('WeCreaBundle\Form\ArtistType', $artist);
        $artistImageForm = $this->createForm('WeCreaBundle\Form\ImagesType', $image);
        $workImageForm = $this->createForm('WeCreaBundle\Form\ImagesWorkType', $image);
        $workForm = $this->createForm('WeCreaBundle\Form\WorkType', $work);
        $lastWorksImageForm = $this->createForm('WeCreaBundle\Form\LastImagesWorkType', $image);

        if($request->isXmlHttpRequest()){

            $idWork = $request->request->get('wecreabundle_work')['id'];
            $idArtist = $request->request->get('wecreabundle_artist')['id'];
            $natureId = $request->request->get('wecreabundle_work')['nature'];

            /* If the user updates a work */
            if(isset($idWork) && isset($natureId)){

                $work = $em->getRepository('WeCreaBundle:Work')->findOneById($id);
                $workForm = $this->createForm('WeCreaBundle\Form\WorkType', $work);
                $workForm->handleRequest($request);
                $nature = $em->getRepository('WeCreaBundle:Nature')->findOneById($natureId);
                $work->setNature($nature);

                $em->flush();

                return new Response("L'oeuvre ".$work->getTitle()." a bien été mise à jour");
            }

            /* If the user updates the artist profile */
            if(isset($idArtist)){

                $artist = $em->getRepository('WeCreaBundle:Artist')->findOneById($id);
                $artistForm = $this->createForm('WeCreaBundle\Form\ArtistType', $artist);
                $artistForm->handleRequest($request);

                $em->flush();

                return new Response("Le profil de " .$artist->getFirstname(). " " .$artist->getName(). " a bien été mis à jour");
            }
        }

        /* If there's at least on completed form
        * If not, that means that the administrator has previously
        * removed all the works in the edit form
        */
        if(!empty($editWorkForms)) {
            return $this->render('@WeCrea/Admin/artist_work_edition.html.twig', array(
                /* The artist */
                'artist' => $artist,
                /* The works */
                'works' => $works,
                /* Completed artist form */
                'editArtistForm' => $editArtistForm->createView(),
                /* New image form for the artist profile */
                'formImage' => $artistImageForm->createView(),
                /* Completed work forms */
                'editWorkForms' => $editWorkForms,
                /* new work form */
                'formWork' => $workForm->createView(),
                /* new image form for a new work */
                'workImageForm' => $workImageForm->createView(),
                /* New image form for existed works */
                'lastWorksImageForm' => $lastWorksImageForm->createView()
            ));
        }
        else {
            return $this->render('@WeCrea/Admin/artist_work_edition.html.twig', array(
                /* The artist */
                'artist' => $artist,
                /* The works */
                'works' => $works,
                /* Completed artist form */
                'editArtistForm' => $editArtistForm->createView(),
                /* New image form for the artist profile */
                'formImage' => $artistImageForm->createView(),
                /* Completed work forms */
                'formWork' => $workForm->createView(),
                /* new image form for a new work */
                'workImageForm' => $workImageForm->createView(),
                /* New image form for existed works */
                'lastWorksImageForm' => $lastWorksImageForm->createView()
            ));
        }
    }

    //--------Admin Carrousel--------//
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

    //---------Admin Actu--------//
    public function actuAction() {
        $em = $this->getDoctrine()->getManager();
        $actus = $em->getRepository('WeCreaBundle:Actu')->findAll();

        $actu = new Actu();
        $image = new Images();

        $formImages = $this->createForm('WeCreaBundle\Form\ImagesType', $image);
        $formActu = $this->createForm('WeCreaBundle\Form\ActuType', $actu);

        return $this->render('@WeCrea/Admin/actu.html.twig', array(
            'formActu' => $formActu->createView(),
            'formImages' => $formImages->createView(),
            'actus' => $actus,
        ));
    }

    public function addActuAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $actu = new Actu();

        if($request->isXmlHttpRequest()) {
            $content = $request->request->get('wecreabundle_actu')['content'];
            $title = $request->request->get('wecreabundle_actu')['title'];
            $imageId = $request->request->get('images_id');

            $image = $em->getRepository('WeCreaBundle:Images')->findOneById($imageId);
            $date = new \DateTime();

            $actu->setContent($content);
            $actu->setTitle($title);
            $actu->addImage($image);
            $actu->setDate($date);

            $em->persist($actu);
            $em->flush();

            $actus = $em->getRepository('WeCreaBundle:Actu')->findAll();

            $actu = $actus[count($actus)-1];

            $encoders = array(new JsonEncoder()) ;
            $normalizer = array(new ObjectNormalizer()) ;
            $serializer = new Serializer($normalizer, $encoders);

            $jsonActu = $serializer->serialize($actu, 'json');

            $response = new response($jsonActu);

            $response->headers->set('Content-Type', 'application/json');

        }

        return $response;
    }

    public function deleteActuAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $id = $request->query->get('id');

        $actu = $em->getRepository('WeCreaBundle:Actu')->findOneById($id);

        $idImage = $actu->getImages()[0]->getId();
        $image = $em->getRepository('WeCreaBundle:Images')->findOneById($idImage);
        $url = $image->getUrl();

        $path = $this->getParameter('image_directory')."/".$url;

        unlink($path);

        $em->remove($actu);
        $em->remove($image);

        $em->flush();

        return new response('L\'actualité a bien été supprimer');
    }

    public function editActuAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $id = $request->query->get('id');

        $actu = $em->getRepository('WeCreaBundle:Actu')->findOneById($id);

        $image = $actu->getImages()[0];

        $formActu = $this->createForm('WeCreaBundle\Form\ActuType', $actu);
        $formImage = $this->createForm('WeCreaBundle\Form\ImagesType', $image);

        return $this->render('@WeCrea/Admin/actu.html.twig', array(
            'formActu' => $formActu->createView(),
            'formImage' => $formImage->createView(),
        ));

    }

    public function adminHomeAction() {
        return $this->render('@WeCrea/Admin/home_admin.html.twig');
    }
}
