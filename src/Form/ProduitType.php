<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\souscategorie;
use App\Entity\unit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('designation')
            ->add('description')
            ->add('qteStock')
            ->add('seuilAlert')
            ->add('unite', EntityType::class, [
                'class' => unit::class,
                'choice_label' => 'id',
            ])
            ->add('sousCategorie', EntityType::class, [
                'class' => souscategorie::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
