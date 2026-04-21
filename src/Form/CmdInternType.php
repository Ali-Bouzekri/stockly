<?php

namespace App\Form;

use App\Entity\CmdIntern;
use App\Entity\Fonctionnaire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CmdInternType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateCI')
            ->add('statut')
            ->add('fonctionnaire', EntityType::class, [
                'class' => Fonctionnaire::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CmdIntern::class,
        ]);
    }
}
