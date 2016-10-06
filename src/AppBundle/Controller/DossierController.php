<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Dossier;
use AppBundle\Form\DossierType;

/**
 * Dossier controller.
 *
 * @Route("/dossier")
 */
class DossierController extends Controller {

    /**
     * Recupère la liste de toutes les occurences de Dossier.
     *
     * @Route("/", name="dossier_index")
     * @Method("GET")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $dossiers = $em->getRepository('AppBundle:Dossier')->findAll();

        return $this->render('dossier/index.html.twig', array(
                    'dossiers' => $dossiers,
        ));
    }

    /**
     * Crée une nouvelle instance de Dossier.
     *
     * @Route("/new", name="dossier_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $dossier = new Dossier();
        $dossier->setDateDebutTraitement(new \DateTime());
        $dossier->setDateFinTraitementPrevu(new \DateTime());
        $dossier->setUtilisateurDerniereModification($this->getUser());
        $form = $this->createForm('AppBundle\Form\DossierType', $dossier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dossier->setDateDerniereModification(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($dossier);
            $em->flush();
            $request->getSession()->getFlashBag()
                    ->set('success', 'Ajout effectué avec succés');

            return $this->redirectToRoute('dossier_show', array('id' => $dossier->getId()));
        }

        return $this->render('dossier/new.html.twig', array(
                    'dossier' => $dossier,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Recupère et affiche un élément Dossier.
     *
     * @Route("/{id}", name="dossier_show")
     * @Method("GET")
     */
    public function showAction(Dossier $dossier) {
        $deleteForm = $this->createDeleteForm($dossier);

        return $this->render('dossier/show.html.twig', array(
                    'dossier' => $dossier,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Affiche un formulaire de modification pour un element Dossier existant.
     *
     * @Route("/{id}/edit", name="dossier_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Dossier $dossier) {
        $deleteForm = $this->createDeleteForm($dossier);
        $editForm = $this->createForm('AppBundle\Form\DossierType', $dossier);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($dossier);
            $em->flush();
            $request->getSession()->getFlashBag()
                    ->set('success', 'Modification effectuée avec succés');

            return $this->redirectToRoute('dossier_show', array('id' => $dossier->getId()));
        }

        return $this->render('dossier/edit.html.twig', array(
                    'dossier' => $dossier,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Supprime une occurence de Dossier.
     *
     * @Route("/{id}", name="dossier_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Dossier $dossier) {
        $form = $this->createDeleteForm($dossier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($dossier);
            $em->flush();
            $request->getSession()->getFlashBag()
                    ->set('dangers', 'Suppression reussie !!!');
        }

        return $this->redirectToRoute('dossier_index');
    }

    /**
     * Supprime la selection de Dossier.
     *
     * @Route("/deleteSelection", name="dossier_deleteSelection")
     * @Method("POST")
     */
    public function deleteSelectionAction(Request $request) {
        $selections = $request->get('selection');
        $em = $this->getDoctrine()->getManager();
        foreach ($selections as $id => $valeur) {
            $element = $em->getRepository('AppBundle:Dossier')->find($id);
            $em->remove($element);
        }
        $em->flush();

        return $this->redirectToRoute('dossier_index');
    }

    /**
     * Crée un formulaire de suppression de Dossier.
     *
     * @param Dossier $dossier The Dossier entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Dossier $dossier) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('dossier_delete', array('id' => $dossier->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
