<?php

namespace WeCreaBundle\Controller;

use WeCreaBundle\Entity\Nature;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Nature controller.
 *
 */
class NatureController extends Controller
{
    /**
     * Lists all nature entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $natures = $em->getRepository('WeCreaBundle:Nature')->findAll();

        return $this->render('nature/index.html.twig', array(
            'natures' => $natures,
        ));
    }

    /**
     * Creates a new nature entity.
     *
     */
    public function newAction(Request $request)
    {
        $nature = new Nature();
        $form = $this->createForm('WeCreaBundle\Form\NatureType', $nature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($nature);
            $em->flush();

            return $this->redirectToRoute('nature_show', array('id' => $nature->getId()));
        }

        return $this->render('nature/new.html.twig', array(
            'nature' => $nature,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a nature entity.
     *
     */
    public function showAction(Nature $nature)
    {
        $deleteForm = $this->createDeleteForm($nature);

        return $this->render('nature/show.html.twig', array(
            'nature' => $nature,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing nature entity.
     *
     */
    public function editAction(Request $request, Nature $nature)
    {
        $deleteForm = $this->createDeleteForm($nature);
        $editForm = $this->createForm('WeCreaBundle\Form\NatureType', $nature);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('nature_edit', array('id' => $nature->getId()));
        }

        return $this->render('nature/edit.html.twig', array(
            'nature' => $nature,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a nature entity.
     *
     */
    public function deleteAction(Request $request, Nature $nature)
    {
        $form = $this->createDeleteForm($nature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($nature);
            $em->flush();
        }

        return $this->redirectToRoute('nature_index');
    }

    /**
     * Creates a form to delete a nature entity.
     *
     * @param Nature $nature The nature entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Nature $nature)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('nature_delete', array('id' => $nature->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
