<?php

namespace WeCreaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class UserController extends Controller
{
    public function indexAction()
    {
        $session = $this->get('session');

        $em = $this->getDoctrine()->getManager();
        $carrousels = $em->getRepository('WeCreaBundle:Carrousel')->findAll();

        $bCount = $this->container->get('app.basket');
        $bCount = $bCount->countBasket($session);

        return $this->render('WeCreaBundle:User:index.html.twig', array(
            'carrousels' => $carrousels,
            'bCount' => $bCount
        ));
    }

    public function worksShowAction() {
        $session = $this->get('session');

        $em = $this->getDoctrine()->getManager();
        $works = $em->getRepository('WeCreaBundle:Work')->findBy(array(), array('id'=>'desc'));

        $bCount = $this->container->get('app.basket');
        $bCount = $bCount->countBasket($session);

        return $this->render('WeCreaBundle:User:works.html.twig', array(
            'bCount' => $bCount,
            'works' => $works,
        ));
    }

    public function workShowAction($id) {
        $em = $this->getDoctrine()->getManager();

        $session = $this->get('session');

        $work = $em->getRepository('WeCreaBundle:Work')->findOneById($id);
        $images = $work->getImages();

        $bCount = $this->container->get('app.basket');
        $bCount = $bCount->countBasket($session);

        return $this->render('WeCreaBundle:User:work.html.twig', array(
            'bCount' => $bCount,
            'work' => $work,
            'images' => $images,
        ));
    }

    /* Method for displaying all the artists registered */
    public function artistsShowAction(){
        $em = $this->getDoctrine()->getManager();

        $session = $this->get('session');

        $bCount = $this->container->get('app.basket');
        $bCount = $bCount->countBasket($session);

        $artists = $em->getRepository('WeCreaBundle:Artist')->findBy(array(),
            array(
                'name' => 'ASC'
        ));

        return $this->render('WeCreaBundle:User:artists.html.twig', array(
            'bCount' => $bCount,
            'artists' => $artists
        ));
    }

    /* Method for displaying a specific artist */
    public function artistShowAction($id){
        $em = $this->getDoctrine()->getManager();

        $session = $this->get('session');

        $bCount = $this->container->get('app.basket');
        $bCount = $bCount->countBasket($session);

        $artist = $em->getRepository('WeCreaBundle:Artist')->findOneById($id);

        return $this->render('WeCreaBundle:User:artist.html.twig', array(
            'artist' => $artist,
            'bCount' => $bCount,
        ));
    }

    /* Method for add a product in basket */
    public function addBasketAction(Request $request) {
        $session = $this->get('session');
        $req = $request->request;
        $idWork = $req->get('work');
        $quant = $req->get('quantity');
        $pBasket = $session->get('basket');

        if (isset($pBasket[$idWork])){
            $quant += $pBasket[$idWork];
        }

        $basket[$idWork] = $quant;
        $session->set('basket', array(
           $idWork => $quant
        ));

        $bCount = $this->container->get('app.basket');
        $bCount = $bCount->countBasket($session);
        
        $response = new Response($bCount);
        return $response;
    }
}
