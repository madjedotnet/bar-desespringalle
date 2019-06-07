<?php

namespace App\Form;

use App\Entity\Album;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class old_AlbumType extends ApplicationType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add(
                'title', 
                TextType::class, 
                $this->getConfiguration("Titre", "Taper un titre pour ce nouvel album !"))
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
                'creationDate'
            )
            ->add(
                'families', EntityType::class, [
                    'class' => Family::class,
                    'choice_label' => 'name',
                    'multiple' => true
                ]
            )
            ->add('mediaFiles', FileType::class, [
                'required' => false,
                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
        ]);
    }
}
