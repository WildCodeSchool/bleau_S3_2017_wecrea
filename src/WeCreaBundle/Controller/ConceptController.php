<?php

namespace WeCreaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use WeCreaBundle\Entity\Concept;
use WeCreaBundle\Form\ConceptType;

class ConceptController extends Controller
{
    /**
     * Edit Concept section
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $conceptPage = $em->getRepository(Concept::class)->getConceptPage();

        $form = $this->createForm(ConceptType::class, $conceptPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
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
