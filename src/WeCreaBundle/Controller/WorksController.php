<?php

namespace WeCreaBundle\Controller;

use function PHPSTORM_META\type;
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
     * @return Response
     */
    public function deleteWorkAction(Work $work, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        foreach ($work->getImages() as $img){
            $this->get('uploader')->deleteImg($img);
        }

        $em->remove($work);
        $em->flush();

        return $this->redirectToRoute('we_crea_admin_works_artist', array(
            'id' => $work->getArtist()->getId()
        ));

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

	            if ($caract->getWeigth() == '' || $caract->getWeigth() == 0){
		            $caract->setWeigth(NULL);
	            }

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

    public function editWorkAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $image = new Images();
        $caract = new Caract();

        $imageForm = $this->createForm(ImagesType::class, $image);
        $formCaract = $this->createForm(CaractType::class, $caract);

        $work = $em->getRepository('WeCreaBundle:Work')->findOneById($id);

        $caracts = $em->getRepository('WeCreaBundle:Caract')->findByWork(array(
            'work' => $work
        ));

        $editWorkForm = $this->createForm('WeCreaBundle\Form\WorkType', $work);

        if ($request->isXmlHttpRequest()) {

            $workForm = $request->request->get('wecreabundle_work');
            $natureId = $request->request->get('wecreabundle_work')['nature'];
            $newImg = $request->request->get('wecreabundle_images');
            $newCaract = $request->request->get('wecreabundle_caract');

            /* If the user updates a work */
            if (isset($workForm)) {

                $editWorkForm->handleRequest($request);
                $nature = $em->getRepository('WeCreaBundle:Nature')->findOneById($natureId);
                $work->setNature($nature);

                $em->flush();

                return new Response("L'oeuvre " . $work->getTitle() . " a bien été mise à jour");
            }

            if (isset($newImg)) {
                $imageForm->handleRequest($request);
                $file = $image->getUrl();
                $this->get('uploader')->uploadImg($file, $image);
                $em->persist($image);

                $work = $em->getRepository(Work::class)->findOneById($id);
                $work->addImage($image);
                $url = $image->getUrl();

                $em->flush();

                return new JsonResponse(array('url' => $url));
            }

            if (isset($newCaract)){
                $formCaract->handleRequest($request);
	            if (!is_numeric($caract->getWeigth()))
		            $caract->setWeigth(null);
                $caract->setWork($work);
                $work->addCaract($caract);

                $em->persist($caract);
                $em->flush();

                $response['idCaract'] = $caract->getId();
                $response['caract'] = array(
                    'dimension' => $caract->getDimension(),
                    'price' => $caract->getPrice(),
                    'weight' => $caract->getWeigth(),
                    'quantity' => $caract->getQuantity()
                );

                return new JsonResponse($response);
            }
        }

        return $this->render('@WeCrea/Admin/artist/works/works_edit.html.twig', array(
            'edit_form' => $editWorkForm->createView(),
            'caracts' => $caracts,
            'formCaract' => $formCaract->createView(),
            'image_form' => $imageForm->createView(),
            'work' => $work,
        ));
    }

    public function editCaractAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$idCaract = $request->request->get('idcaract');
    	$qt = $request->request->get('qt');
    	$dimension = $request->request->get('dimension');
    	$price = $request->request->get('price');
    	$wheight = $request->request->get('wheight');

    	$caract = $em->getRepository(Caract::class)->findOneBy(array('id' => $idCaract));

    	$caract->setDimension($dimension);
    	$caract->setQuantity($qt);

    	if ($wheight == '' || $wheight == 0){
		    $caract->setWeigth(NULL);
	    }
	    else{
		    $caract->setWeigth($wheight);
	    }
	    $caract->setPrice($price);


    	$em->flush();

    	return new Response('ok');
    }

    public function deleteWorkImageAjaxAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        /* Stores the work id */
        $idWork = $request->request->get('idWork');
        /* Looks for the work corresponding to the id */
        $work = $em->getRepository('WeCreaBundle:Work')->findOneById($idWork);
        /* Stores the image id */
        $idImg = $request->request->get('idImg');
        /* Looks for the image corresponding to the id */
        $image = $em->getRepository('WeCreaBundle:Images')->findOneByUrl($idImg);
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

    public function deleteCaractAction(Request $request){

        if($request->isXMLHttpRequest()){
            $idCaract = $request->request->get('idCaract');

            $em = $this->getDoctrine()->getManager();
            $caract = $em->getRepository('WeCreaBundle:Caract')->findOneById($idCaract);
            $em->remove($caract);
            $em->flush();

            return new JsonResponse(array('msg' => "Les caractéristiques ont bien été supprimées"));
        }
    }
}