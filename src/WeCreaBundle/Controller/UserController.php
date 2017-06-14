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

        $container = $this->container;

        $bCount = $container->get('app.basket')->countBasket($session);
        $fCount = $container->get('favs')->countFavs($session);

        return $this->render('WeCreaBundle:User:index.html.twig', array(
            'carrousels' => $carrousels,
            'bCount' => $bCount,
            'fCount' =>$fCount,
        ));
    }

    public function conceptAction() {
        $session = $this->get('session');

        $container = $this->container;

        $bCount = $container->get('app.basket')->countBasket($session);
        $fCount = $container->get('favs')->countFavs($session);

        return $this->render('@WeCrea/User/concept.html.twig', array(
            'bCount' => $bCount,
            'fCount' => $fCount,

        ));
    }
    public function worksShowAction() {
        $session = $this->get('session');

        $em = $this->getDoctrine()->getManager();
        $works = $em->getRepository('WeCreaBundle:Work')->findBy(array(), array('id'=>'desc'));

        $container = $this->container;

        $favs = $session->get('favs');

        $bCount = $container->get('app.basket')->countBasket($session);
        $fCount = $container->get('favs')->countFavs($session);

        return $this->render('WeCreaBundle:User:works.html.twig', array(
            'bCount' => $bCount,
            'fCount' =>$fCount,
            'works' => $works,
            'favs' => $favs
        ));
    }

    public function workShowAction($id) {
        $em = $this->getDoctrine()->getManager();

        $session = $this->get('session');

        $work = $em->getRepository('WeCreaBundle:Work')->findOneById($id);
        $images = $work->getImages();

        $container = $this->container;

        $bCount = $container->get('app.basket')->countBasket($session);
        $fCount = $container->get('favs')->countFavs($session);

        return $this->render('WeCreaBundle:User:work.html.twig', array(
            'bCount' => $bCount,
            'work' => $work,
            'images' => $images,
            'fCount' => $fCount
        ));
    }

    /* Method for displaying all the artists registered */
    public function artistsShowAction(){
        $em = $this->getDoctrine()->getManager();

        $session = $this->get('session');

        $container = $this->container;

        $bCount = $container->get('app.basket')->countBasket($session);
        $fCount = $container->get('favs')->countFavs($session);

        $artists = $em->getRepository('WeCreaBundle:Artist')->findBy(array(),
            array(
                'name' => 'ASC'
        ));

        return $this->render('WeCreaBundle:User:artists.html.twig', array(
            'bCount' => $bCount,
            'artists' => $artists,
            'fCount' => $fCount
        ));
    }

    /* Method for displaying a specific artist */
    public function artistShowAction($id){
        $em = $this->getDoctrine()->getManager();

        $session = $this->get('session');

        $container = $this->container;

        $bCount = $container->get('app.basket')->countBasket($session);
        $fCount = $container->get('favs')->countFavs($session);

        $artist = $em->getRepository('WeCreaBundle:Artist')->findOneById($id);

        return $this->render('WeCreaBundle:User:artist.html.twig', array(
            'artist' => $artist,
            'bCount' => $bCount,
            'fCount' => $fCount
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
            $pBasket[$idWork] += $quant;
        }
        else {
            $pBasket [$idWork] = $quant;
        }

        $session->set('basket', $pBasket);

        return $this->redirectToRoute('we_crea_works');
    }

    public function showBasketAction() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');

        $bWorks =[];

        $container = $this->container;

        $bCount = $container->get('app.basket')->countBasket($session);
        $fCount = $container->get('favs')->countFavs($session);

        $works = $em->getRepository('WeCreaBundle:Work');
        $pBasket = $session->get('basket');
        if(isset($pBasket)) {
            foreach ($pBasket as $idWork => $quant) {
                $work = $works->findOneById($idWork);
                $bWorks [] = array(
                    'work' => $work,
                    'quant' => $quant
                );
            }
        }

        return $this->render('@WeCrea/User/basket/summary.html.twig', array(
            'works' => $bWorks,
            'bCount' => $bCount,
            'fCount' => $fCount
        ));
    }

    /* ----- Add Favs -----*/
    public function addFavAction(Request $request) {
        $session = $this->get('session');
        $favs = $session->get('favs');


        $idWork = $request->request->get('idWork');
        $workName =
            $this->getDoctrine()->getManager()
            ->getRepository('WeCreaBundle:Work')->findOneById($idWork)
            ->getTitle()
        ;

        if (!isset($favs[$idWork])){
            $favs[$idWork] = $workName;
        }

        $session->set('favs', $favs);

        $fCount = $this->container->get('favs')->countFavs($session);

        $content = array(
            'fCount' => $fCount,
            'name' => $workName
        );

        $response = new Response(json_encode($content));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /* ----- delete favs -----*/
    public function deleteFavAction(Request $request) {
        $session = $this->get('session');

        $idFav = $request->request->get('idWork');
        $favs = $session->get('favs');
        unset($favs[$idFav]);
        $session->set('favs', $favs);

        $workName =
            $this->getDoctrine()->getManager()
                ->getRepository('WeCreaBundle:Work')->findOneById($idFav)
                ->getTitle()
        ;

        $fCount = $this->container->get('favs')->countFavs($session);

        $content = array(
            'fCount' => $fCount,
            'name' => $workName
        );

        $response = new Response(json_encode($content));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /* ----- show favs ----- */
    public function showFavsAction() {
        $session = $this->get('session');
        $works = $this->getDoctrine()->getManager()->getRepository('WeCreaBundle:Work');
        $container = $this->container;

        $bCount = $container->get('app.basket')->countBasket($session);
        $fCount = $container->get('favs')->countFavs($session);

        $favObjs = [];
        $favs = $session->get('favs');

        if (isset($favs)) {
            foreach ($favs as $key => $fav) {
                $favObj = $works->findOneById($key);
                $favObjs [] = $favObj;
            }
        }

        return $this->render('@WeCrea/User/favs.html.twig', array(
            'favs' => $favObjs,
            'bCount' => $bCount,
            'fCount' => $fCount
        ));
    }
}
