<?php

namespace JMB\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', TextType::class, [
                'label' => 'label.username',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('_password', PasswordType::class, [
                'label' => 'label.password',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('_remember_me', CheckboxType::class, [
                'label' => 'label.remember_me',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'button.submit_login',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'csrf_protection' => false,
                'remember_me' => false,
                'translation_domain' => 'JMBUserBundle',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return '';
    }
}
