<?php

namespace App\Form;

use App\Entity\CmdIntern;
use App\Entity\Fonctionnaire;
use App\Entity\LivIntern;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivInternType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateLI')
            ->add('cmdInt', EntityType::class, [
                'class' => CmdIntern::class,
                'choice_label' => 'id',
            ])
            ->add('receveur', EntityType::class, [
                'class' => Fonctionnaire::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LivIntern::class,
        ]);
    }
}
