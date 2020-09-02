<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                "constraints" => [
                                new NotBlank(["message" => "Vous avez oublié de remplir le titre d'évenement"]),
                                new Length([
                                            "min" => 1,
                                            "max" => 50,
                                            "minMessage" => "",
                                            "maxMessage" => "Le titre ne doit pas dépasser 50 caractères"])
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

            ->add('lieu', TextType::class, [
                "constraints" => [
                                new NotBlank(["message" => "Vous avez oublié de remplir le titre d'évenement"]),
                                new Length([
                                            'max' => 255,
                                            'maxMessage' => 'L\'adresse email ne peut contenir plus de {{ limit }} caractéres.']),
                            ]
                ])
            ->add('date', DateTimeType::class, [
                "widget" => "single_text",
                "constraints" => [
                                new NotBlank(["message" => "Vous avez oublié de rajouter une date"]),
                            ]
                ])
            ->add('price')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
