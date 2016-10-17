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
class PieceJointeController extends Controller {

    /**
     * Recupère la liste de toutes les occurences de PieceJointe.
     *
     * @Route("/", name="piecejointe_index")
     * @Method("GET")
     */
    public function indexAction() {
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
    public function newAction(Request $request) {
        $pieceJointe = new PieceJointe();
        $form = $this->createForm('AppBundle\Form\PieceJointeType', $pieceJointe);
        $form->handleRequest($request);
        $dossier = $pieceJointe->getDossier();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pieceJointe);
            $users_concernes = $em->createQuery('select u from AppBundle:User u, AppBundle:TraitementDossier td '
                            . 'where td.user=u and td.dossier=?1 ')
                    ->setParameter(1, $dossier)
                    ->getResult();
            foreach ($users_concernes as $users_concerne) {
                if ($users_concerne != $this->getUser()) {
                    $notification = new \AppBundle\Entity\Notification();
                    $notification->setDate(new \DateTime());
                    $notification->setUser($users_concerne);
                    $notification->setLibelle('Ajout de pièce jointe au dossier ' . $dossier);
                    $notification->setContenu("L'utilisateur " . $this->getUser() . " a ajouté une nouvelle pièce jointe au  "
                            . " dossier n° " . $dossier->getId() . ". ayant pour nom de dossier: " . $dossier->getDossier() . ". "
                            . "Son dégré d'importance est " . $dossier->getDegreImportance() . ". La pièce jointe a pour libellé " . $pieceJointe->getLibelle()
                            . "  Nota Béné: Vous avez eu à travailler sur ce dossier, c'est pourquoi nous vous alertons à chaque opération importante .");
                    $notification->setDossier($dossier);
                    $notification->setSource($this->getUser());
                    $notification->setEtat(0);
                    $em->persist($notification);
                }
            }
            $em->flush();
            $request->getSession()->getFlashBag()
                    ->set('success', 'Ajout effectué avec succés');

            return $this->redirectToRoute('dossier_show', array('id' => $pieceJointe->getDossier()->getId()));
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
    public function showAction(PieceJointe $pieceJointe) {
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
    public function editAction(Request $request, PieceJointe $pieceJointe) {
        $deleteForm = $this->createDeleteForm($pieceJointe);
        $editForm = $this->createForm('AppBundle\Form\PieceJointeType', $pieceJointe);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $dossier = $pieceJointe->getDossier();
            $users_concernes = $em->createQuery('select u from AppBundle:User u, AppBundle:TraitementDossier td '
                            . 'where td.user=u and td.dossier=?1 ')
                    ->setParameter(1, $dossier)
                    ->getResult();
            foreach ($users_concernes as $users_concerne) {
                if ($users_concerne != $this->getUser()) {
                    $notification = new \AppBundle\Entity\Notification();
                    $notification->setDate(new \DateTime());
                    $notification->setUser($users_concerne);
                    $notification->setLibelle('Modification de pièce jointe du dossier ' . $dossier);
                    $notification->setContenu("L'utilisateur " . $this->getUser() . " a modifié une pièce jointe associée au "
                            . " dossier n° " . $dossier->getId() . ". ayant pour nom de dossier: " . $dossier->getDossier() . ". "
                            . "Son dégré d'importance est " . $dossier->getDegreImportance() . ". La pièce jointe a pour libellé " . $pieceJointe->getLibelle()
                            . ".  Nota Béné: Vous avez eu à travailler sur ce dossier, c'est pourquoi nous vous alertons à chaque opération importante .");
                    $notification->setDossier($dossier);
                    $notification->setSource($this->getUser());
                    $notification->setEtat(0);
                    $em->persist($notification);
                }
            }
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
    public function deleteAction(Request $request, PieceJointe $pieceJointe) {
        $form = $this->createDeleteForm($pieceJointe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($pieceJointe);
            $dossier = $pieceJointe->getDossier();
            $users_concernes = $em->createQuery('select u from AppBundle:User u, AppBundle:TraitementDossier td '
                            . 'where td.user=u and td.dossier=?1 ')
                    ->setParameter(1, $dossier)
                    ->getResult();
            foreach ($users_concernes as $users_concerne) {
                if ($users_concerne != $this->getUser()) {
                    $notification = new \AppBundle\Entity\Notification();
                    $notification->setDate(new \DateTime());
                    $notification->setUser($users_concerne);
                    $notification->setLibelle('Suppression de pièce jointe du dossier ' . $dossier);
                    $notification->setContenu("L'utilisateur " . $this->getUser() . " a supprimé une pièce jointe associée au "
                            . " dossier n° " . $dossier->getId() . ". ayant pour nom de dossier: " . $dossier->getDossier() . ". "
                            . "Son dégré d'importance est " . $dossier->getDegreImportance() . ". La pièce jointe a pour libellé " . $pieceJointe->getLibelle()
                            . ".  Nota Béné: Vous avez eu à travailler sur ce dossier, c'est pourquoi nous vous alertons à chaque opération importante .");
                    $notification->setDossier($dossier);
                    $notification->setSource($this->getUser());
                    $notification->setEtat(0);
                    $em->persist($notification);
                }
            }
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
    public function deleteSelectionAction(Request $request) {
        $selections = $request->get('selection');
        $em = $this->getDoctrine()->getManager();
        foreach ($selections as $id => $valeur) {
            $element = $em->getRepository('AppBundle:PieceJointe')->find($id);
            $dossier = $element->getDossier();
            $users_concernes = $em->createQuery('select u from AppBundle:User u, AppBundle:TraitementDossier td '
                            . 'where td.user=u and td.dossier=?1 ')
                    ->setParameter(1, $dossier)
                    ->getResult();
            foreach ($users_concernes as $users_concerne) {
                if ($users_concerne != $this->getUser()) {
                    $notification = new \AppBundle\Entity\Notification();
                    $notification->setDate(new \DateTime());
                    $notification->setUser($users_concerne);
                    $notification->setLibelle('Suppression de pièce jointe du dossier ' . $dossier);
                    $notification->setContenu("L'utilisateur " . $this->getUser() . " a supprimé une pièce jointe associée au "
                            . " dossier n° " . $dossier->getId() . ". ayant pour nom de dossier: " . $dossier->getDossier() . ". "
                            . "Son dégré d'importance est " . $dossier->getDegreImportance() . ". La pièce jointe a pour libellé " . $element->getLibelle()
                            . ".  Nota Béné: Vous avez eu à travailler sur ce dossier, c'est pourquoi nous vous alertons à chaque opération importante .");
                    $notification->setDossier($dossier);
                    $notification->setSource($this->getUser());
                    $notification->setEtat(0);
                    $em->persist($notification);
                }
            }
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
    private function createDeleteForm(PieceJointe $pieceJointe) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('piecejointe_delete', array('id' => $pieceJointe->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
