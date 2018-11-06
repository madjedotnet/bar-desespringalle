<?php

namespace App\Form;

use App\Entity\Album;
use App\Form\PictureType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AlbumType extends AbstractType
{
    /**
     * Permet d'avoir la configuration de base d'un champ
     * 
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    private function getConfiguration($label, $placeholder, $options = []) {
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
            ->add(
                'creationUser')
            ->add(
                'pictures', 
                CollectionType::class, [
                    'entry_type' => PictureType::class,
                    'allow_add' => true
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
