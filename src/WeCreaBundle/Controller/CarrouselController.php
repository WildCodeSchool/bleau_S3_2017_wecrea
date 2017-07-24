<?php

namespace WeCreaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use WeCreaBundle\Entity\Carrousel;
use WeCreaBundle\Form\CarrouselType;

class CarrouselController extends Controller {
    /**
     * Edit Carrousel
     * @return Response
     */
    public function editCarrouselAction() {
        $em = $this->getDoctrine()->getManager();

        $carrousels = $em->getRepository('WeCreaBundle:Carrousel')->findAll();

        $carrousel = new Carrousel();

        $formCarrousel = $this->createForm('WeCreaBundle\Form\CarrouselType', $carrousel);

        return $this->render('@WeCrea/Admin/carrousel_edition.html.twig', array(
            'formCarrousel' => $formCarrousel->createView(),
            'carrousels' => $carrousels,
        ));
    }


    /**
     * Add carrousel
     * @param Request $request
     * @return Response
     */
    public function addCarrouselAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $carrousel = new Carrousel();

        $form = $this->createForm(CarrouselType::class, $carrousel);
        $form->handleRequest($request);

        if($request->isXmlHttpRequest()) {

            $file = $carrousel->getImages()->getUrl();

            $this->get('uploader')->upload($file, $carrousel);

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


    /**
     * Delete carrousel
     * @param Request $request
     * @return Response
     */
    public function deleteCarrouselAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $id = $request->query->get('id');

        $carrousel = $em->getRepository('WeCreaBundle:Carrousel')->findOneById($id);

        $em->remove($carrousel);

        $em->flush();

        return new Response('La vignette a bien été supprimer');
    }
}