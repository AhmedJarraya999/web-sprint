<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class BookingType extends AbstractType
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
            ]);
        # ->add('user');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
