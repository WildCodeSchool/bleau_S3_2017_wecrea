<?php

namespace WeCreaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use WeCreaBundle\Entity\Artist;
use WeCreaBundle\Entity\Work;


class WorksController extends Controller
{
    /**
     * List all works for one artist
     * @param Artist $artist
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAllArtistWorksAction(Artist $artist)
    {
        return $this->render('@WeCrea/Admin/artist/works/wroks.html.twig', array(
            'artist' => $artist
        ));
    }

    public function deleteWorkAction(Work $work)
    {
        $em = $this->getDoctrine()->getManager();
        if ($work){

            foreach ($work->getImages() as $img){
                $this->get('uploader')->deleteImg($img);
            }

            $em->remove($work);
            $em->flush();

            return new Response('ok');
        }
    }
}