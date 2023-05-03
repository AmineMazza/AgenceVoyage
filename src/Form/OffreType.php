<?php

namespace App\Form;

use App\Entity\Offre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Image;

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
                    new Image([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                            'image/heic',
                        ],
                        'minWidth' => 1080,
                        'minHeight' => 1920,
                        'maxWidth' => 1080,
                        'maxHeight' => 1920,
                        'mimeTypesMessage' => 'Please upload a valid image file (PNG, JPEG, JPG)',
                        'maxWidthMessage' => 'The image width has to be {{ max_width }} pixels.',
                        'maxHeightMessage' => 'The image height has to be {{ max_height }} pixels.',
                        'minWidthMessage' => 'The image width has to be {{ min_width }} pixels.',
                        'minHeightMessage' => 'The image height has to be {{ min_height }} pixels.',
                    ])
                ],
            ])
            ->add('id_destination', null ,[ 'attr' => [ 'class' => "OffreDestination" ]])
            ->add('date_depart')
            ->add('date_retour')
            ->add('hotels',CollectionType::class, [
                'entry_type' => HotelType::class,
                'required' => false,
                'attr' => [
                    'class' => 'hidden',
                ],
                'label' => false,
                'entry_options' => ['label' => false],
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'error_bubbling' => false
            ])
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
            ->add('prix')
            ->add('detail_voyage',TextareaType::class, ['required' => false])
            ->add('detail_vols',TextareaType::class, ['required' => false])
            ->add('bpassport')
            ->add('bphotos')
            ->add('bpass_vacinial')
            ->add('bcoup_coeur')
            ->add('bpubier')
            ->add('date_publication')
            ->add('date_fin_publication')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
