<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\Stay;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AdminBookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('firstDate', DateTimeType::class, [
                'data' => new \DateTime(),
                'attr'  => ['min' => (new \DateTime())->format('Y-m-d')]
            ])
            ->add('endDate',  DateTimeType::class, [
                'data' => new \DateTime(),
                'attr'  => ['min' => (new \DateTime())->format('Y-m-d')]
            ])
            ->add('stay', EntityType::class, ['class' => Stay::class, 'choice_label' => 'id'])
            ->add('user', EntityType::class, ['class' => User::class, 'choice_label' => 'firstname']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
