<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('imageFile', VichImageType::class, [
                'label' => "télecharger une image",
                'download_label' => 'Telecharger',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer votre profil image',
                'download_uri' => true,
                'image_uri' => true,
                'asset_helper' => true,
            ])
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
