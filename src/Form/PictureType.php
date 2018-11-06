<?php

namespace App\Form;

use App\Entity\Picture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class PictureType extends AbstractType
{
    /**
     * Permet d'avoir la configuration de base d'un champ
     * 
     * @param string $label
     * @param string $placeholder
     * @return array
     */
    private function getConfiguration($label, $placeholder) {
        return [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ];
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('location', UrlType::class, [
                'attr' => [
                    'placeholder' => "Url de l'image"
                ]
            ])
            ->add('caption', TextType::class, [
                'attr' => [
                    'placeholder' => "Titre de l'image"
                ]
            ])
            ->add('disposition', TextType::class, [
                'attr' => [
                    'placeholder' => "Orientation de l'image"
                ]
            ])
            // dans le cas d'ajout directement dans le formulaire de l'album
            // ->add('album')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Picture::class,
        ]);
    }
}
