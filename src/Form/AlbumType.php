<?php

namespace App\Form;

use App\Entity\Album;
use App\Form\PictureType;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class AlbumType extends ApplicationType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add(
                'title', 
                TextType::class, 
                $this->getConfiguration("Titre", "Taper un titre pour ce nouvel album !")
            )
            ->add(
                'slug', 
                TextType::class, 
                $this->getConfiguration("Titre", "Taper un titre pour ce nouvel album !", 
                ['required' => false]))
            ->add(
                'albumDate')
            ->add(
                'content', 
                TextareaType::class, $this->getConfiguration("Description", 
                "Taper une description pour ce nouvel album !"))
            ->add(
                'creationDate')
                ->add('photos',
                FileType::class, [
                    'mapped' => false,
                    'label' => 'Please choose your photos...',
                    'multiple' => true
                ])
            ->add(
                'pictures', 
                CollectionType::class, [
                    'entry_type' => PictureType::class,
                    'allow_add' => true,
                    'allow_delete' => true
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
        ]);
    }
}
