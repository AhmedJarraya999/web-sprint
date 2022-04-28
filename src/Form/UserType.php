<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('password')
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('phone')
            ->add('introduction', TextareaType::class)
            ->add(
                'roles',
                ChoiceType::class,
                array(
                    'attr'  =>  array(
                        'class' => 'form-controll',
                        'style' => 'margin:5px 0;'
                    ),
                    'choices' => [
                        'Host' => 'ROLE_HOST',
                        'GUEST' => 'ROLE_GUEST',
                        'ADMIN' => 'ROLE_ADMIN',
                    ]
                    //'multiple' => false,
                    //'required' => true,
                )
            )
            ->add('captchaCode', CaptchaType::class, array(
                'captchaConfig' => 'ExampleCaptcha'
            ));

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return $rolesArray[0] ?? null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
