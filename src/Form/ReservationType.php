<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_reservation')
            ->add('num_voyageurs', NumberType::class, ['required' => false])
            ->add('remarque')
            ->add('Mnt_commission')
            ->add('avance_commission')
            ->add('date_avance_commission')
            ->add('id_offre')
            ->add('id_commercial',CommercialType::class, ['required' => false])
            ->add('id_user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
