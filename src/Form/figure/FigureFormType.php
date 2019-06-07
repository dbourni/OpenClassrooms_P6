<?php

namespace App\Form\figure;

use App\Entity\Figure;
use App\Entity\Grp;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FigureFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'rows' => 6,
                ]
            ])
            ->add('grp', EntityType::class, [
                'class' => Grp::class,
                'choice_label' => 'name',
                'multiple' => false,
                'label' => 'Groupe',
            ])
            ->add('pictures', CollectionType::class, [
                'entry_type' => PictureFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
                'prototype' => true,
                'by_reference' => false,
                'required' => false,
            ])
            ->add('videos', CollectionType::class, [
                'entry_type' => VideoFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
                'prototype' => true,
                'by_reference' => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Figure::class,
        ]);
    }
}