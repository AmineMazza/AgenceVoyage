<?php

namespace App\Form;

use App\Entity\Agent;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('logo', FileType::class, array(
                'required' => false,
                'mapped' => false
            ))
            ->add('email', TextType::class, [
                'attr' => ['class' => 'block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer',
                            'id' => 'floating_email',
                            'name' => 'floating_email',
                            'type' => 'email',
                            'placeholder' => " "                        

            ]
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password',
                            'class' => 'block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer',
                            'id' => 'floating_password',
                            'name' => 'floating_password',
                            'type' => 'password',
                            'placeholder' => " "                         
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),

                ],
            ])
            ->add('agence', TextType::class, [
                'required' => false,
                'attr' =>['class' => 'block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer',
                            'id' => 'floating_agence',
                            'name' => 'floating_agence',
                            'type' => 'text',
                            'placeholder' => " "                        

            ],
            ])
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer',
                           'id' => 'floating_nom',
                           'name' => 'floating_nom',
                           'type' => 'text',
                           'placeholder' => " "  
                ]
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer',
                           'id' => 'floating_prenom',
                           'name' => 'floating_prenom',
                           'type' => 'text',
                           'placeholder' => " "  
                ]
            ])
            ->add('telephone_mobile', TextType::class, [
                'attr' => ['class' => 'block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer',
                           'id' => 'floating_tel',
                           'name' => 'floating_tel',
                           'type' => 'tel',
                           'placeholder' => " "  
                ]
            ])
            ->add('telephone_fixe', TextType::class, [
                'required' => false,
                'attr' => ['class' => 'block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer',
                           'id' => 'floating_fixe',
                           'name' => 'floating_fixe',
                           'type' => 'tel',
                           'placeholder' => " "  
                ]
            ])
            ->add('adresse', TextType::class, [
                'required' => false,
                'attr' => ['class' => 'block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer',
                            'id' => 'floating_adresse',
                            'name' => 'floating_adresse',
                            'type' => 'text',
                            'placeholder' => " "                        

            ],
            ])
            ->add('type_abonnement', ChoiceType::class, [
                'choices' => [
                    'type d\'abonnement: ' => null,
                    'free' => 'free',
                    'premium' => 'premium',
                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'absolute h-12 left-96 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800']
            ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
