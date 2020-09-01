<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class InscriptionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                        new NotBlank(['message' => 'Email manquant']),
                        new Length([
                                        'max' => 180,
                                        'maxMessage' => 'L\'adresse email ne peut contenir plus de {{ limit }} caractéres.'
                        ]),
                        new Email(['message' => 'cette adresse n\'est pas une adresse email valide.']),
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message'  => 'les mots de passe est incorrecte. Réessayer!',

                // instead of being set onto the object directly, (le chmp n'est pas lié à l'objet User formuliare)
                // this is read and encoded in the controller (Le MDP sera hashé depuis le controlleur)
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Mot de passe manquant.',
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                        'message' => 'Veuillez saisir un mot de passe composé de 8 caractères dont une MAJ, une minuscule, un chiffre et une caractère spécial (-!?$#@).',
                        ]),

                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractéres.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                               ])
                ]
            ])
            ->add('pseudo', TextType::class, [
                'constraints' => [
                                    new NotBlank(['message' => 'Pseudo manquant.']),
                                    new Length([
                                                    'min' => 3,
                                                    'minMessage' => 'Le pseudo doit contenir au moin {{ limit }} caractéres.',
                                                    'max' => 30,
                                                    'minMessage' => 'Le pseudo ne peut pas contenir plus de {{ limit }} caractéres.',
                                    ]),
                                    new Regex([
                                                    'pattern' => '/^[a-zA-Z0-9_-]+$/',
                                                    'message' => 'Le pseudo ne peut contenir que des chiffres , lettres, tirets et underscores.',
                                    ])
                                ]
            ])

            ->add('nom', TextType::class, [
            "constraints" => [
                            new NotBlank(["message" => "Vous avez oublié de remplir ce champ"]),
                            new Length([
                                        "min" => 1,
                                        "max" => 40,
                                        "minMessage" => "",
                                        "maxMessage" => "Le nom ne doit pas dépasser 40 caractères"])
                            ]
            ])

            ->add('prenom', TextType::class, [
            "constraints" => [
                            new NotBlank(["message" => "Vous avez oublié de remplir ce champ"]),
                            new Length([
                                        "min" => 1,
                                        "max" => 40,
                                        "minMessage" => "Le prénom doit contenir au moins 1 caractères",
                                        "maxMessage" => "Le prénom ne doit pas dépasser 40 caractères"])
                            ]
            ])

            ->add('telephone', TextType::class, [
            "constraints" => [
                            new NotBlank(["message" => "Vous avez oublié de remplir ce champ"]),
                            new Length([
                                        "min" => 10, 
                                        "max" => 10,
                                        "minMessage" => "Le numéro de téléphone doit contenir 10 chiffres",
                                        "maxMessage" => "Le numéro de téléphone doit contenir 10 chiffres"])
                            ]
            ])

            ->add('civilite', ChoiceType::class, [
                                                    'choices' => [
                                                                    'Homme' => 'H',
                                                                    'Femme' => 'F',
                                                                ]
            ])

        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
