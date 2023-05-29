<?php

namespace App\Form;

use App\Entity\Offre;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Image;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre',TextType::class,['required' => false])
            ->add('image',FileType::class,[
                'label' => 'image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '2024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                            'image/heic',
                        ],
                        // 'minWidth' => 1080,
                        // 'minHeight' => 1920,
                        // 'maxWidth' => 1080,
                        // 'maxHeight' => 1920,
                        'mimeTypesMessage' => 'Please upload a valid image file (PNG, JPEG, JPG)',
                        'maxWidthMessage' => 'The image width has to be {{ max_width }} pixels.',
                        'maxHeightMessage' => 'The image height has to be {{ max_height }} pixels.',
                        'minWidthMessage' => 'The image width has to be {{ min_width }} pixels.',
                        'minHeightMessage' => 'The image height has to be {{ min_height }} pixels.',
                    ])
                ],
            ])
            ->add('id_destination', null ,['required' => false, 'attr' => [ 'class' => "OffreDestination" ]])
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
            ->add('bhebergement',null,['required' => false])
            ->add('bvisa',null,['required' => false])
            ->add('bdemi_pension',null,['required' => false])
            ->add('prix_demi_pension',NumberType::class,['required' => false])
            ->add('detail_demi_pension',TextareaType::class, ['required' => false])
            ->add('bpension_complete',null,['required' => false])
            ->add('prix_complete_pension',NumberType::class,['required' => false])
            ->add('detail_complete_pension',TextareaType::class, ['required' => false])
            ->add('bvisite_medine',null,['required' => false])
            ->add('prix_un',NumberType::class,['required' => false])
            ->add('prix_double',NumberType::class,['required' => false])
            ->add('prix_triple',NumberType::class,['required' => false])
            ->add('prix_quad',NumberType::class,['required' => false])
            ->add('prix_quint',NumberType::class,['required' => false])
            ->add('detail_voyage',TextareaType::class, ['required' => false])
            ->add('detail_vols',TextareaType::class, ['required' => false])
            ->add('bpassport',null,['required' => false])
            ->add('bphotos',null,['required' => false])
            ->add('bpass_vacinial',null,['required' => false])
            ->add('bcoup_coeur',null,['required' => false])
            ->add('bpubier',null,['required' => false])
            ->add('date_publication',DateType::class,['required' => false])
            ->add('date_fin_publication',DateType::class,['required' => false])
            ->add('id_user', null ,[    
                'placeholder' => "",
                'required' => false,
                'query_builder' => function ($repository) {
                    return $repository->createQueryBuilder('u')
                        ->where('u.roles LIKE :role')
                        ->setParameter('role', '%ROLE_AGENT%');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
            'allow_extra_fields' => true,
        ]);
    }
}
