<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Commentaire;
use AppBundle\Form\CommentaireType;

/**
 * Commentaire controller.
 *
 * @Route("/commentaire")
 */
class CommentaireController extends Controller
{
    /**
     * Recupère la liste de toutes les occurences de Commentaire.
     *
     * @Route("/", name="commentaire_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $commentaires = $em->getRepository('AppBundle:Commentaire')->findAll();

        return $this->render('commentaire/index.html.twig', array(
            'commentaires' => $commentaires,
        ));
    }

    /**
     * Crée une nouvelle instance de Commentaire.
     *
     * @Route("/new", name="commentaire_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $commentaire = new Commentaire();
        $form = $this->createForm('AppBundle\Form\CommentaireType', $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
            $request->getSession()->getFlashBag()
            ->set('success', 'Ajout effectué avec succés');

            return $this->redirectToRoute('commentaire_show', array('id' => $commentaire->getId()));
        }

        return $this->render('commentaire/new.html.twig', array(
            'commentaire' => $commentaire,
            'form' => $form->createView(),
        ));
    }

    /**
     * Recupère et affiche un élément Commentaire.
     *
     * @Route("/{id}", name="commentaire_show")
     * @Method("GET")
     */
    public function showAction(Commentaire $commentaire)
    {
        $deleteForm = $this->createDeleteForm($commentaire);

        return $this->render('commentaire/show.html.twig', array(
            'commentaire' => $commentaire,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Affiche un formulaire de modification pour un element Commentaire existant.
     *
     * @Route("/{id}/edit", name="commentaire_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Commentaire $commentaire)
    {
        $deleteForm = $this->createDeleteForm($commentaire);
        $editForm = $this->createForm('AppBundle\Form\CommentaireType', $commentaire);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
            $request->getSession()->getFlashBag()
            ->set('success', 'Modification effectuée avec succés');

            return $this->redirectToRoute('commentaire_show', array('id' => $commentaire->getId()));
        }

        return $this->render('commentaire/edit.html.twig', array(
            'commentaire' => $commentaire,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Supprime une occurence de Commentaire.
     *
     * @Route("/{id}", name="commentaire_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Commentaire $commentaire)
    {
        $form = $this->createDeleteForm($commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($commentaire);
            $em->flush();
            $request->getSession()->getFlashBag()
            ->set('dangers', 'Suppression reussie !!!');
        }

        return $this->redirectToRoute('commentaire_index');
    }
    
    
    
    
    /**
     * Supprime la selection de Commentaire.
     *
     * @Route("/deleteSelection", name="commentaire_deleteSelection")
     * @Method("POST")
     */
    public function deleteSelectionAction(Request $request)
    {
        $selections=$request->get('selection');
        $em=  $this->getDoctrine()->getManager();
        foreach ($selections as $id=>$valeur){
            $element=$em->getRepository('AppBundle:Commentaire')->find($id);
            $em->remove($element);
        }
        $em->flush();

        return $this->redirectToRoute('commentaire_index');
    }


    
    
    
    
    
    

    /**
     * Crée un formulaire de suppression de Commentaire.
     *
     * @param Commentaire $commentaire The Commentaire entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Commentaire $commentaire)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('commentaire_delete', array('id' => $commentaire->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
