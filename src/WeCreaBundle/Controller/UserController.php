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
        $works = $em->getRepository('WeCreaBundle:Work')->findAll();
    }
}
