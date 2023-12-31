<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('num_voyageurs', NumberType::class)
            ->add('remarque', TextareaType::class, ['required' => false])
            ->add('mnt_commission', TextType::class, ['required' => false])
            ->add('avance_commission', TextType::class, ['required' => false])
            ->add('voyageurs',CollectionType::class, [
                'entry_type' => VoyageurType::class,
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'hidden',
                ],
                'entry_options' => ['label' => false],
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'error_bubbling' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'allow_extra_fields' => true,
        ]);
    }
}
