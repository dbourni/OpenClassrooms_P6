<?php

namespace App\Form\security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class NewPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('password2', PasswordType::class, [
                'label' => 'Saisissez de nouveau votre mot de passe',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
        ;
    }
}