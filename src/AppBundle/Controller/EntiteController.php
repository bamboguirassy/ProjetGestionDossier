<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Entite;
use AppBundle\Form\EntiteType;

/**
 * Entite controller.
 *
 * @Route("/entite")
 */
class EntiteController extends Controller
{
    /**
     * Recupère la liste de toutes les occurences de Entite.
     *
     * @Route("/", name="entite_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entites = $em->getRepository('AppBundle:Entite')->findAll();

        return $this->render('entite/index.html.twig', array(
            'entites' => $entites,
        ));
    }

    /**
     * Crée une nouvelle instance de Entite.
     *
     * @Route("/new", name="entite_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $entite = new Entite();
        $form = $this->createForm('AppBundle\Form\EntiteType', $entite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entite);
            $em->flush();
            $request->getSession()->getFlashBag()
            ->set('success', 'Ajout effectué avec succés');

            return $this->redirectToRoute('entite_show', array('id' => $entite->getId()));
        }

        return $this->render('entite/new.html.twig', array(
            'entite' => $entite,
            'form' => $form->createView(),
        ));
    }

    /**
     * Recupère et affiche un élément Entite.
     *
     * @Route("/{id}", name="entite_show")
     * @Method("GET")
     */
    public function showAction(Entite $entite)
    {
        $deleteForm = $this->createDeleteForm($entite);

        return $this->render('entite/show.html.twig', array(
            'entite' => $entite,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Affiche un formulaire de modification pour un element Entite existant.
     *
     * @Route("/{id}/edit", name="entite_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Entite $entite)
    {
        $deleteForm = $this->createDeleteForm($entite);
        $editForm = $this->createForm('AppBundle\Form\EntiteType', $entite);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entite);
            $em->flush();
            $request->getSession()->getFlashBag()
            ->set('success', 'Modification effectuée avec succés');

            return $this->redirectToRoute('entite_show', array('id' => $entite->getId()));
        }

        return $this->render('entite/edit.html.twig', array(
            'entite' => $entite,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Supprime une occurence de Entite.
     *
     * @Route("/{id}", name="entite_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Entite $entite)
    {
        $form = $this->createDeleteForm($entite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($entite);
            $em->flush();
            $request->getSession()->getFlashBag()
            ->set('dangers', 'Suppression reussie !!!');
        }

        return $this->redirectToRoute('entite_index');
    }
    
    
    
    
    /**
     * Supprime la selection de Entite.
     *
     * @Route("/deleteSelection", name="entite_deleteSelection")
     * @Method("POST")
     */
    public function deleteSelectionAction(Request $request)
    {
        $selections=$request->get('selection');
        $em=  $this->getDoctrine()->getManager();
        foreach ($selections as $id=>$valeur){
            $element=$em->getRepository('AppBundle:Entite')->find($id);
            $em->remove($element);
        }
        $em->flush();

        return $this->redirectToRoute('entite_index');
    }


    
    
    
    
    
    

    /**
     * Crée un formulaire de suppression de Entite.
     *
     * @param Entite $entite The Entite entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Entite $entite)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('entite_delete', array('id' => $entite->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
