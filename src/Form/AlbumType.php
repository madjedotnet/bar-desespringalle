<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Album;
use App\Entity\Family;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('slug')
            ->add('title')
            ->add('content')
            ->add('albumDate')
            ->add('creationDate')
            ->add('author')
            ->add('families', EntityType::class, [
                'class' => Family::class,
                'choice_label' => 'name',
                'multiple' => true
            ])
            ->add('mediaFiles', FileType::class, [
                'required' => false,
                'multiple' => true
            ])
            ->add('users', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'firstName',
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
