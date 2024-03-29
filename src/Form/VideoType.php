<?php

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints as Assert;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class, [
            'label' => 'Titre',
            // 'constraints' => [
            //     new Assert\LessThan(20)
            // ]

        ])
        ->add('videoLink', TextType::class, [
            'label' => 'Lien vidéo (YouTube)',
        ])
        ->add('description', TextareaType::class, [
            'label' => 'Description',
            // 'constraints' => [
            //     new Assert\LessThan(20)
            // ]

        ])
        ->add('isPremiumVideo', CheckboxType::class, [
            'attr' => [
                'class' => 'form-check-input',
            ],
            'required' => false,
            'label' => 'est une video premium? ',
            'label_attr' => [
                'class' => 'form-check-label'
            ],
            'constraints' => [
                new Assert\NotNull()
            ]
        ])
        ->add('submit', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-primary mt-4'
            ],
            'label' => 'Créer une vidéo'
        ]);

    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
