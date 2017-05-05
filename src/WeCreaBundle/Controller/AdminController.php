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

class AdminController extends Controller
{
    public function newArtistWorkAction(Request $request)
    {
        $artist = new Artist();
        $image = new Images();

        $formArtist = $this->createForm('WeCreaBundle\Form\ArtistType', $artist);
        $formImageArtist = $this->createForm('WeCreaBundle\Form\ImagesType', $image);

        $formArtist->handleRequest($request);

        if ($formArtist->isSubmitted() && $formArtist->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($artist);
            $em->flush();
        }

        return $this->render('@WeCrea/Admin/artist_work_creation.html.twig', array(
            'artist' => $artist,
            'form' => $formArtist->createView(),
            'formImage' => $formImageArtist->createView(),
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
        //$artist = new Artist();

        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $alt = $request->request->get('wecreabundle_images')['alt'];
            $file = $request->files->get('wecreabundle_images')['url'];

            $fileName = uniqId() . '.' . $file->guessExtension();
            $file->move($this->getParameter('image_directory'), $fileName);

            $image->setUrl($fileName);
            $image->setAlt($alt);

            $em->persist($image);
            $em->flush();

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
        $em = $this->getDoctrine()->getManager();

        $request = $request;
        dump($request);
        $id = $request->request->get('id');

        $image = $em->getRepository('WeCreaBundle:Images')->findOneById($id);

        $url = $image->getUrl();

        $path = $this->getParameter('image_directory')."/".$url;

        if(file_exists($path)){
            unlink($path);
        }

        $em->remove($image);
        $em->flush();

        return new Response("L'image a bien été supprimée");
    }

}