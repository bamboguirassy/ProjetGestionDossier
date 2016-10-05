<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\UserEntite;
use AppBundle\Form\UserEntiteType;

/**
 * UserEntite controller.
 *
 * @Route("/userentite")
 */
class UserEntiteController extends Controller
{
    /**
     * Recupère la liste de toutes les occurences de UserEntite.
     *
     * @Route("/", name="userentite_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userEntites = $em->getRepository('AppBundle:UserEntite')->findAll();

        return $this->render('userentite/index.html.twig', array(
            'userEntites' => $userEntites,
        ));
    }

    /**
     * Crée une nouvelle instance de UserEntite.
     *
     * @Route("/new", name="userentite_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $userEntite = new UserEntite();
        $form = $this->createForm('AppBundle\Form\UserEntiteType', $userEntite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($userEntite);
            $em->flush();
            $request->getSession()->getFlashBag()
            ->set('success', 'Ajout effectué avec succés');

            return $this->redirectToRoute('userentite_show', array('id' => $userEntite->getId()));
        }

        return $this->render('userentite/new.html.twig', array(
            'userEntite' => $userEntite,
            'form' => $form->createView(),
        ));
    }

    /**
     * Recupère et affiche un élément UserEntite.
     *
     * @Route("/{id}", name="userentite_show")
     * @Method("GET")
     */
    public function showAction(UserEntite $userEntite)
    {
        $deleteForm = $this->createDeleteForm($userEntite);

        return $this->render('userentite/show.html.twig', array(
            'userEntite' => $userEntite,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Affiche un formulaire de modification pour un element UserEntite existant.
     *
     * @Route("/{id}/edit", name="userentite_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, UserEntite $userEntite)
    {
        $deleteForm = $this->createDeleteForm($userEntite);
        $editForm = $this->createForm('AppBundle\Form\UserEntiteType', $userEntite);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($userEntite);
            $em->flush();
            $request->getSession()->getFlashBag()
            ->set('success', 'Modification effectuée avec succés');

            return $this->redirectToRoute('userentite_show', array('id' => $userEntite->getId()));
        }

        return $this->render('userentite/edit.html.twig', array(
            'userEntite' => $userEntite,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Supprime une occurence de UserEntite.
     *
     * @Route("/{id}", name="userentite_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, UserEntite $userEntite)
    {
        $form = $this->createDeleteForm($userEntite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($userEntite);
            $em->flush();
            $request->getSession()->getFlashBag()
            ->set('dangers', 'Suppression reussie !!!');
        }

        return $this->redirectToRoute('userentite_index');
    }
    
    
    
    
    /**
     * Supprime la selection de UserEntite.
     *
     * @Route("/deleteSelection", name="userentite_deleteSelection")
     * @Method("POST")
     */
    public function deleteSelectionAction(Request $request)
    {
        $selections=$request->get('selection');
        $em=  $this->getDoctrine()->getManager();
        foreach ($selections as $id=>$valeur){
            $element=$em->getRepository('AppBundle:UserEntite')->find($id);
            $em->remove($element);
        }
        $em->flush();

        return $this->redirectToRoute('userentite_index');
    }


    
    
    
    
    
    

    /**
     * Crée un formulaire de suppression de UserEntite.
     *
     * @param UserEntite $userEntite The UserEntite entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(UserEntite $userEntite)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('userentite_delete', array('id' => $userEntite->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
