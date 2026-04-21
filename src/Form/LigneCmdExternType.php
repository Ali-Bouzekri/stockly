<?php

namespace App\Form;

use App\Entity\CmdExtern;
use App\Entity\LigneCmdExtern;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LigneCmdExternType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantite')
            ->add('cmdExtern', EntityType::class, [
                'class' => CmdExtern::class,
                'choice_label' => 'id', // Adjust this to the property you want to display in the dropdown
            ])
            ->add('produit', EntityType::class, [
                'class' => Produit::class,
                'choice_label' => 'idProduit', // Adjust this to the property you want to display in the dropdown
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LigneCmdExtern::class,
        ]);
    }
}
