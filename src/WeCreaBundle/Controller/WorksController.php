<?php

namespace WeCreaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    public function deleteWorkAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->query->get('id');
        $work = $em->getRepository('WeCreaBundle:Work')->findOneById($id);

        if ($work){

            foreach ($work->getImages() as $img){
                $this->get('uploader')->deleteImg($img);
            }

            $em->remove($work);
            $em->flush();
            $response = new JsonResponse(array(
                'id' => $id
            ));
            return $response;
        }

    }
}