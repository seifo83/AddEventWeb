<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class MailParticipeFormType extends AbstractType
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
        ->add('description', TextareaType::class, [
            "constraints" => [
                            new NotBlank(["message" => "Vous avez oublié de remplir le titre d'évenement"]),
                            new Length([
                                        'max' => 255,
                                        'maxMessage' => 'L\'adresse email ne peut contenir plus de {{ limit }} caractéres.']),
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
