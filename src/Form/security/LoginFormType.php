<?php

namespace App\Form\security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginFormType extends AbstractType
{
    /**
     * @var string|null
     */
    protected $lastUsername;

    /**
     * LoginFormType constructor.
     *
     * @param null $lastUsername
     */
    public function __construct($lastUsername = null)
    {
        $this->lastUsername = $lastUsername;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', TextType::class, ['label' => 'Email', 'attr' => [
                'placeholder' => 'Entrez votre email', 'value' => $this->lastUsername, 'autofocus' => true
            ]])
            ->add('_password', PasswordType::class, ['label' => 'Mot de passe', 'attr' => [
                'placeholder' => 'Entrez votre mot de passe', 'name' => '_password'
            ]])
            ->add('_remember_me', CheckboxType::class, [
                'label' => 'Se souvenir de moi', 'required' => false
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return null;
    }
}