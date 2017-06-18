<?php

namespace WeCreaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WeCreaBundle\Entity\Artist;
use WeCreaBundle\Entity\Caract;
use WeCreaBundle\Entity\Images;
use WeCreaBundle\Entity\Work;
use WeCreaBundle\Form\CaractType;
use WeCreaBundle\Form\ImagesType;
use WeCreaBundle\Form\WorkType;


class WorksController extends Controller
{
    /**
     * List all works for one artist
     * @param Artist $artist
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAllArtistWorksAction(Artist $artist)
    {
        return $this->render('@WeCrea/Admin/artist/works/works_list.html.twig', array(
            'artist' => $artist
        ));
    }

    /**
     * Delete one work and there picture
     * @param Request $request
     * @return JsonResponse
     */
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

    /**
     * Create Work
     * @param Request $request
     * @param Artist $artist
     * @return Response
     */
    public function addWorkAction(Request $request, Artist $artist)
    {
        $work = new Work();
        $image = new Images();
        $caract = new Caract();

        $formWork = $this->createForm(WorkType::class, $work);
        $formImage = $this->createForm(ImagesType::class, $image);
        $formCaract = $this->createForm(CaractType::class, $caract);

        if ($request->isXmlHttpRequest()) {

            $em = $this->getDoctrine()->getManager();

            $response = array();
            $newWork = $request->request->get('wecreabundle_work');
            $newImg = $request->request->get('wecreabundle_images');
            $newCarac = $request->request->get('wecreabundle_caract');

//            If create caract work
            if (isset($newWork)){
                $formWork->handleRequest($request);
                $work->setArtist($artist);
                $em->persist($work);
            }

//            If add image for work
            if (isset($newImg)) {
                $formImage->handleRequest($request);
                $file = $image->getUrl();
                $this->get('uploader')->uploadImg($file, $image);
                $em->persist($image);

                $id = $request->request->get('idWork');
                $work = $em->getRepository(Work::class)->findOneById($id);
                $work->addImage($image);
                $url = $image->getUrl();

                $response = array('url' => $url);
            }

            if (isset($newCarac)){
                $formCaract->handleRequest($request);

                $id = $request->request->get('idWork');
                $work = $em->getRepository(Work::class)->findOneById($id);

                $caract->setWork($work);
                $em->persist($caract);
                $em->flush();

                $response['idCaract'] = $caract->getId();
                $response['caract'] = array(
                    'dimension' => $caract->getDimension(),
                    'price' => $caract->getPrice(),
                    'weight' => $caract->getWeigth(),
                    'quantity' => $caract->getQuantity()
                );
            }

            $em->flush();
            $response['idWork'] = $work->getId();

            return new JsonResponse($response);

        }

        return $this->render('@WeCrea/Admin/artist/works/works_add.html.twig', array(
            'formWork' => $formWork->createView(),
            'formImage' => $formImage->createView(),
            'formCaract' => $formCaract->createView(),
            'artist' => $artist
        ));
    }
}