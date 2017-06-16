<?php

namespace WeCreaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WeCreaBundle\Entity\Artist;
use WeCreaBundle\Entity\Images;
use WeCreaBundle\Form\ArtistType;
use WeCreaBundle\Form\ImagesType;

class ArtistController extends Controller
{
    /**
     * Render the page for listing all the artists
     * @return Response
     */
    public function artistListAction()
    {

        $em = $this->getDoctrine()->getManager();
        $artists = $em->getRepository('WeCreaBundle:Artist')->findAll();

        return $this->render('@WeCrea/Admin/artist/artist_list.html.twig', array(
            'artists' => $artists
        ));
    }

    /**
     * @param Request $request
     * @param Artist $artist
     * @return array|JsonResponse|Response
     */
    public function editArtistAction(Request $request, Artist $artist)
    {
        $em = $this->getDoctrine()->getManager();

        $image = new Images();
        $form = $this->createForm(ArtistType::class, $artist);
        $formImage = $this->createForm(ImagesType::class, $image);

        if ($request->isXmlHttpRequest()) {

            $response = [];
            $newArtistImage = $request->request->get('wecreabundle_images');
            $form->handleRequest($request);

            if (isset($newArtistImage)) {
                $formImage->handleRequest($request);
                $file = $image->getUrl();

                $this->get('uploader')->uploadImg($file, $image);
                $em->persist($image);

                $artist->addImage($image);
                $url = $image->getUrl();
                $response = array('url' => $url);
            }

            $em->flush();

            $idArtist = $artist->getId();
            $response ['idArt'] = $idArtist;
            $response ['msg'] = "Le profil de " . $artist->getFirstname() . " " . $artist->getName() . " a bien été mis à jour";

            $response = new JsonResponse($response);

            return $response;
        }

        return $this->render('@WeCrea/Admin/artist/edit_artist.html.twig', array(
            'form' => $form->createView(),
            'formImage' => $formImage->createView(),
            'artist' => $artist
        ));
    }

    /**
     * Delete one artist and all his her pictures
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteArtistAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $artist = $em->getRepository('WeCreaBundle:Artist')->findOneById($id);

        $works = $em->getRepository('WeCreaBundle:Work')->findByArtist(array(
            'artist' => $artist
        ));

        foreach ($works as $work) {
            $images = $work->getImages();
            foreach ($images as $image) {
                $img = $image->getUrl();
                $file = $this->getParameter('image_directory') . "/" . $img;

                if (file_exists($file)) {
                    unlink($file);
                }
            }
            $em->remove($work);
        }

        $em->remove($artist);
        $em->flush();

        return $this->redirectToRoute('we_crea_admin_artist_list');

    }

    /**
     * Create Artist
     * @param Request $request
     * @return Response
     */
    public function addArtistAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $artist = new Artist();
        $image = new Images();

        $formArtist = $this->createForm('WeCreaBundle\Form\ArtistType', $artist);
        $formImageArtist = $this->createForm('WeCreaBundle\Form\ImagesType', $image);

        if ($request->isXmlHttpRequest()) {

            $response = [];
            $newArtist = $request->request->get('wecreabundle_artist');
            $newArtistImage = $request->request->get('wecreabundle_images');

            if (isset($newArtist)) {
                $formArtist->handleRequest($request);
                $em->persist($artist);
            }
            if (isset($newArtistImage)) {

                $formImageArtist->handleRequest($request);
                $file = $image->getUrl();

                $this->get('uploader')->uploadImg($file, $image);
                $em->persist($image);

                $id = $request->request->get('idArt');
                $artist = $em->getRepository('WeCreaBundle:Artist')->findOneById($id);

                $artist->addImage($image);
                $url = $image->getUrl();
                $response = array('url' => $url);
            }

            $em->flush();

            $idArtist = $artist->getId();
            $response ['idArt'] = $idArtist;

            $response = new JsonResponse($response);

            return $response;
        }
        return $this->render('@WeCrea/Admin/artist/addArtist.html.twig', array(
            'form' => $formArtist->createView(),
            'formImage' => $formImageArtist->createView()
        ));
    }


    /**
     * Method for deleting an image from the artist profile
     * @param Request $request
     * @return Response
     */
    public function deleteArtistImageAjaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /* If the artist profile has been created */
        $idArt = $request->request->get('idArt');
        $artist = $em->getRepository('WeCreaBundle:Artist')->findOneById($idArt);

        $Img = $request->request->get('idImg');

        $image = $em->getRepository('WeCreaBundle:Images')->findOneByUrl($Img);
        $url = $image->getUrl();

        /*
        * Let's remove the image linked with the artist
        */
        $artist->removeImage($image);

        // TODO: Add remove img in entity
        $em->remove($image);

        $path = $this->get('uploader')->getTargetDir() . '/' . $url;

        if (file_exists($path)) {
            unlink($path);
        }

        $em->flush();

        return new Response("L'image a bien été supprimée");
    }
}