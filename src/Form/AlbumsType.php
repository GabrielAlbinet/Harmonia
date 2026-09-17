<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Enum\AlbumType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlbumsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', null, [
                'label' => 'Titre de l\'album',
            ])
            ->add('artist', EntityType::class, [
                'class' => Artist::class,
                'choice_label' => 'label',
                'label' => 'Artiste',
            ])
            ->add('type', EnumType::class, [
                'class' => AlbumType::class,
                'label' => 'Type d\'album',
            ])
            ->add('cover', ChoiceType::class, [
                'label' => 'Pochette',
                'choices' => [
                    'Image 1' => 'uploads/1.jpeg',
                    'Image 2' => 'uploads/2.jpeg',
                    'Image 3' => 'uploads/3.jpeg',
                    'Image 4' => 'uploads/4.jpeg',
                    'Image 5' => 'uploads/5.jpeg',
                    'Image 6' => 'uploads/6.jpeg',
                    'Image 7' => 'uploads/7.jpeg',
                    'Image 8' => 'uploads/8.jpeg',
                    'Image 9' => 'uploads/9.jpeg',
                    'Image 10' => 'uploads/10.jpeg',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
        ]);
    }
}