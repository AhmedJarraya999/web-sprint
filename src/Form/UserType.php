<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
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
            /*->add('roles', ChoiceType::class, array(
                'attr'  =>  array('class' => 'form-control',
                'style' => 'margin:5px 0;'),
                'choices' => 
                array
                (
                    'ROLE_ADMIN' => array
                    (
                        'Yes' => 'ROLE_ADMIN',
                    ),
                    'ROLE_HOST' => array
                    (
                        'Yes' => 'ROLE_HOST'
                    ),
                    'ROLE_GUEST' => array
                    (
                        'Yes' => 'ROLE_GUEST'
                    ),
                ) 
                ,
                'multiple' => false,
                'required' => true,
                )
            )*/
            ->add('phone');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
