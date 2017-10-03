<?php

namespace WeCreaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use WeCreaBundle\Entity\Actu;
use WeCreaBundle\Form\ActuType;

class ActuController extends Controller {


    /**
     * Show actualités
     * @return Response
     */
    public function actuAction() {
        $em = $this->getDoctrine()->getManager();
        $actus = $em->getRepository('WeCreaBundle:Actu')->findBy([], ["date" => "DESC"]);

        $actu = new Actu();
        $formActu = $this->createForm('WeCreaBundle\Form\ActuType', $actu);

        return $this->render('@WeCrea/Admin/actu.html.twig', array(
            'formActu' => $formActu->createView(),
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

        $form = $this->createForm(ActuType::class, $actu);

        $form->handleRequest($request);

        if($request->isXmlHttpRequest()) {

            $file = $actu->getImages()->getUrl();

            $this->get('uploader')->upload($file, $actu);

            $date = new \DateTime();
            $actu->setDate($date);

            $em->persist($actu);
            $em->flush();

            $subscribers = $em->getRepository("WeCreaBundle:Subscriber")->findAll();
            $actus = $em->getRepository("WeCreaBundle:Actu")->findAll();
            $actu = $actus[count($actus)-1];

            foreach ($subscribers as $subscriber) {
                $destination = $subscriber->getEmail();
                $message = new \Swift_Message();
                $message
                    ->setSubject('We-Crea - Nouvelle actualité')
                    ->setFrom($this->getParameter('mailer_user'))
                    ->setTo($destination);
                $image = $message->embed(\Swift_Image::fromPath('images/'.$actu->getImages()->getUrl()));
                $message->setBody(
                    $this->renderView('WeCreaBundle:Admin:actu_newsletter.html.twig', array(
                        'actu' => $actu,
                        'image' => $image,
                        'subscriber' => $subscriber
                    )),
                    'text/html'
                );
                $this->get('mailer')->send($message);
            }

            $encoders = new JsonEncoder();
            $normalizer = new ObjectNormalizer();
            $serializer = new Serializer(array($normalizer), array($encoders));
            $jsonActu = $serializer->serialize($actu, "json");

            $response = new Response($jsonActu);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
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

        $image = $actu->getImages();
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
//    public function editActuAction(Request $request) {
//        $em = $this->getDoctrine()->getManager();
//        $id = $request->query->get('id');
//
//        $actu = $em->getRepository('WeCreaBundle:Actu')->findOneById($id);
//
//        $formActu = $this->createForm('WeCreaBundle\Form\ActuType', $actu);
//
//        return $this->render('@WeCrea/Admin/actu.html.twig', array(
//            'formActu' => $formActu->createView(),
//        ));

//    }

    public function editExistingActuAction(Actu $actu, Request $request){

	    $formActu = $this->createForm('WeCreaBundle\Form\ActuType', $actu);

	    $actu->previousImage = $actu->getImages()->getUrl();

	    $formActu->handleRequest($request);

	    if ($formActu->isSubmitted()){

		    $file = $actu->getImages()->getUrl();

		    if ($file != null){
			    $this->get('uploader')->upload($file, $actu);
		    }
		    else{
			    $actu->getImages()->setUrl($actu->previousImage);
		    }

		    $em = $this->getDoctrine()->getManager();
		    $em->flush();

	    	return $this->redirectToRoute('we_crea_admin_actu');
	    }

	    return $this->render('@WeCrea/Admin/actu_edit.html.twig', array(
	    	'form' => $formActu->createView(),
		    'actu' => $actu
	    ));
    }
}
