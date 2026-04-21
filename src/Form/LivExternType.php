<?php

namespace App\Form;

use App\Entity\CmdExtern;
use App\Entity\Comite;
use App\Entity\LivExtern;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivExternType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateLE')
            ->add('cmdExt', EntityType::class, [
                'class' => CmdExtern::class,
                'choice_label' => 'id',
            ])
            ->add('comite', EntityType::class, [
                'class' => Comite::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LivExtern::class,
        ]);
    }
}
