<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DossierType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('degreImportance')
            ->add('dateDebutTraitement', 'datetime')
            ->add('dateFinTraitementPrevu', 'datetime')
            ->add('dureeMaximumTraitement')
            ->add('etat')
            ->add('dateDerniereModification', 'datetime')
            ->add('utilisateurDerniereModification')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Dossier'
        ));
    }
}
