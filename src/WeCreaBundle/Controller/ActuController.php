<?php

namespace WeCreaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use WeCreaBundle\Entity\Actu;
use WeCreaBundle\Entity\Images;

class ActuController extends Controller {


    /**
     * Show actualités
     * @return Response
     */
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

    /**
     * Add actualité
     * @param Request $request
     * @return Response
     */
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

    /**
     * Delete actualités
     * @param Request $request
     * @return Response
     */
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

    /**
     * Edit actualité
     * @param Request $request
     * @return Response
     */
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
}
