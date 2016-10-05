<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\PieceJointe;
use AppBundle\Form\PieceJointeType;

/**
 * PieceJointe controller.
 *
 * @Route("/piecejointe")
 */
class PieceJointeController extends Controller
{
    /**
     * Recupère la liste de toutes les occurences de PieceJointe.
     *
     * @Route("/", name="piecejointe_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $pieceJointes = $em->getRepository('AppBundle:PieceJointe')->findAll();

        return $this->render('piecejointe/index.html.twig', array(
            'pieceJointes' => $pieceJointes,
        ));
    }

    /**
     * Crée une nouvelle instance de PieceJointe.
     *
     * @Route("/new", name="piecejointe_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $pieceJointe = new PieceJointe();
        $form = $this->createForm('AppBundle\Form\PieceJointeType', $pieceJointe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pieceJointe);
            $em->flush();
            $request->getSession()->getFlashBag()
            ->set('success', 'Ajout effectué avec succés');

            return $this->redirectToRoute('piecejointe_show', array('id' => $pieceJointe->getId()));
        }

        return $this->render('piecejointe/new.html.twig', array(
            'pieceJointe' => $pieceJointe,
            'form' => $form->createView(),
        ));
    }

    /**
     * Recupère et affiche un élément PieceJointe.
     *
     * @Route("/{id}", name="piecejointe_show")
     * @Method("GET")
     */
    public function showAction(PieceJointe $pieceJointe)
    {
        $deleteForm = $this->createDeleteForm($pieceJointe);

        return $this->render('piecejointe/show.html.twig', array(
            'pieceJointe' => $pieceJointe,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Affiche un formulaire de modification pour un element PieceJointe existant.
     *
     * @Route("/{id}/edit", name="piecejointe_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PieceJointe $pieceJointe)
    {
        $deleteForm = $this->createDeleteForm($pieceJointe);
        $editForm = $this->createForm('AppBundle\Form\PieceJointeType', $pieceJointe);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pieceJointe);
            $em->flush();
            $request->getSession()->getFlashBag()
            ->set('success', 'Modification effectuée avec succés');

            return $this->redirectToRoute('piecejointe_show', array('id' => $pieceJointe->getId()));
        }

        return $this->render('piecejointe/edit.html.twig', array(
            'pieceJointe' => $pieceJointe,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Supprime une occurence de PieceJointe.
     *
     * @Route("/{id}", name="piecejointe_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, PieceJointe $pieceJointe)
    {
        $form = $this->createDeleteForm($pieceJointe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($pieceJointe);
            $em->flush();
            $request->getSession()->getFlashBag()
            ->set('dangers', 'Suppression reussie !!!');
        }

        return $this->redirectToRoute('piecejointe_index');
    }
    
    
    
    
    /**
     * Supprime la selection de PieceJointe.
     *
     * @Route("/deleteSelection", name="piecejointe_deleteSelection")
     * @Method("POST")
     */
    public function deleteSelectionAction(Request $request)
    {
        $selections=$request->get('selection');
        $em=  $this->getDoctrine()->getManager();
        foreach ($selections as $id=>$valeur){
            $element=$em->getRepository('AppBundle:PieceJointe')->find($id);
            $em->remove($element);
        }
        $em->flush();

        return $this->redirectToRoute('piecejointe_index');
    }


    
    
    
    
    
    

    /**
     * Crée un formulaire de suppression de PieceJointe.
     *
     * @param PieceJointe $pieceJointe The PieceJointe entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PieceJointe $pieceJointe)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('piecejointe_delete', array('id' => $pieceJointe->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
