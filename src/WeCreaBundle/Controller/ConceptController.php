<?php

namespace WeCreaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use WeCreaBundle\Entity\Concept;
use WeCreaBundle\Form\ConceptType;

class ConceptController extends Controller
{
    public function editAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $conceptPage = $em->getRepository(Concept::class)->getConceptPage();

        $form = $this->createForm(ConceptType::class, $conceptPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $file=$conceptPage->getImages()->getUrl();
            $this->get('uploader')->upload($file, $conceptPage);

            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Section concept modifié avec succès');
            return $this->redirectToRoute('we_crea_admin_homepage');
        }

        return $this->render('WeCreaBundle:Admin:edit_concept.html.twig', array(
            'form' => $form->createView(),
            'concept' => $conceptPage
        ));
    }
}
