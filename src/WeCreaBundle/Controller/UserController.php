<?php

namespace WeCreaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $carrousels = $em->getRepository('WeCreaBundle:Carrousel')->findAll();

        return $this->render('WeCreaBundle:User:index.html.twig', array(
            'carrousels' => $carrousels,
        ));
    }

    public function workShowAction() {
        $em = $this->getDoctrine()->getManager();
        $works = $em->getRepository('WeCreaBundle:Work')->findBy(array(), array('id'=>'desc'));

        return $this->render('WeCreaBundle:User:work.html.twig', array(
            'works' => $works,
        ));
    }

    /* Method for displaying all the artists registered */
    public function artistsShowAction(){
        $em = $this->getDoctrine()->getManager();
        $artists = $em->getRepository('WeCreaBundle:Artist')->findBy(array(),
            array('name' => 'ASC'
        ));

        return $this->render('WeCreaBundle:User:artists.html.twig', array(
           'artists' => $artists
        ));
    }

    /* Method for displaying a specific artist */
    public function artistShowAction($id){
        $em = $this->getDoctrine()->getManager();
        $artist = $em->getRepository('WeCreaBundle:Artist')->findOneById($id);

        return $this->render('WeCreaBundle:User:artist.html.twig', array(
            'artist' => $artist
        ));
    }
}
