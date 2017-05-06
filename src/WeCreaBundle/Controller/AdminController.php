<?php

namespace WeCreaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use WeCreaBundle\Entity\Artist;
use WeCreaBundle\Entity\Images;
use WeCreaBundle\Entity\Work;
use WeCreaBundle\Entity\Nature;
use WeCreaBundle\Repository\NatureRepository;

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

        if ($formArtist->isSubmitted() && $formArtist->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($artist);
            $em->flush();
        }

        return $this->render('@WeCrea/Admin/artist_work_creation.html.twig', array(
            'artist' => $artist,
            'form' => $formArtist->createView(),
            'formImage' => $formImageArtist->createView(),
            'formWorkImage' => $formWorkImage->createView(),
            'formWork' => $formWork->createView(),
        ));
    }

    public function editArtistWorkAction(Request $request, Artist $artist)
    {
        $deleteForm = $this->createDeleteForm($artist);
        $editForm = $this->createForm('WeCreaBundle\Form\ArtistType', $artist);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

        }

        return $this->render('@WeCrea/Admin/artist_work_edition.html.twig', array(
            'artist' => $artist,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
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

            $image->setUrl($fileName);
            $image->setAlt($alt);

            /* if the artist profile has been created
            *  before the submission of the new image,
            *  we can make the association
            */
            if(!empty($request->request->get('idArt'))){
                $idArt = $request->request->get('idArt');
                $artist = $em->getRepository('WeCreaBundle:Artist')->findOneById($idArt);
                $artist->addImage($image);
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

    public function newArtistProfilAjaxAction(Request $request){

        $artist = new Artist();

        if($request->isXmlHttpRequest()){
            $em = $this->getDoctrine()->getManager();

            $name = $request->request->get('wecreabundle_artist')['name'];
            $firstName = $request->request->get('wecreabundle_artist')['firstname'];
            $localization = $request->request->get('wecreabundle_artist')['localization'];
            $expertise = $request->request->get('wecreabundle_artist')['expertise'];

            /* If images have been created before the submission
            *  we just associate them to the profile
            */

            if(!empty($request->request->get('idImg'))){
                $idImg = $request->request->get('idImg');
                for($i=0; $i<count($idImg); ++$i){
                    $image = $em->getRepository('WeCreaBundle:Images')->findOneById($idImg[$i]);
                    $artist->addImage($image);
                }
            }

            $artist->setName($name);
            $artist->setFirstname($firstName);
            $artist->setLocalization($localization);
            $artist->setExpertise($expertise);

            $em->persist($artist);
            $em->flush();

            /* We send back the data regarding the profile */
            $artist = $em->getRepository('WeCreaBundle:Artist')->findOneBy(array(
                'name'=> $name,
                'firstname' => $firstName
            ));

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
        /* This method takes in charge the deletion of a single image
        *  (user action -- delete image button) as well as all the images
        *  created from the beginning if the client decides to stop the
        *  creation of the artist profile (closes window, goes back to previous page...)
        */

        $em = $this->getDoctrine()->getManager();

        /* If the artist profile has been created */
        if(!empty($request->request->get('idArt'))){
            $idArt = $request->request->get('idArt');
            $artist = $em->getRepository('WeCreaBundle:Artist')->findOneById($idArt);
        }
        else{
            $artist = false;
        }

        /* Array of images = 1 or all images */
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

    public function newWorkImageAjaxAction(Request $request)
    {
        $image = new Images();
        dump($request);
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $alt = $request->request->get('wecreabundle_images')['alt'];
            $file = $request->files->get('wecreabundle_images')['url'];

            $fileName = uniqId() . '.' . $file->guessExtension();
            $file->move($this->getParameter('image_directory'), $fileName);

            $image->setUrl($fileName);
            $image->setAlt($alt);

            /* if the artist profile has been created
            *  before the submission of the new image,
            *  we can make the association
            */
            if(!empty($request->request->get('idArt'))){
                $idArt = $request->request->get('idArt');
                $artist = $em->getRepository('WeCreaBundle:Artist')->findOneById($idArt);
                $artist->addImage($image);
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

}