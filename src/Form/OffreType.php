<?php

namespace App\Form;

use App\Entity\Offre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('image',FileType::class,[
                'label' => 'image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a JPG or JPEG image',
                    ])
                ],
            ])
            ->add('date_depart')
            ->add('date_retour')
            ->add('baller_retour')
            ->add('bhebergement')
            ->add('bvisa')
            ->add('bpetit_dejeuner')
            ->add('bdemi_pension')
            ->add('bpension_complete')
            ->add('bvisite_medine')
            ->add('prix_chambre')
            ->add('prix_chambre_double')
            ->add('prix_chambre_triple')
            ->add('prix_chambre_quad')
            ->add('prix_chambre_quint')
            ->add('bcoup_coeur')
            ->add('bpubier')
            ->add('date_publication')
            ->add('date_fin_publication')
            ->add('bpassport')
            ->add('bphotos')
            ->add('bpass_vacinial')
            ->add('prix')
            ->add('detail_voyage',TextareaType::class)
            ->add('detail_vols',TextareaType::class)
            ->add('id_destination')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
