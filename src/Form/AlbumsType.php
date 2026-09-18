<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Enum\AlbumType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class AlbumsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $constraints = [
            new File(
                maxSize: '2M',
                mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
                mimeTypesMessage: 'Merci de mettre une image valide (JPEG ou PNG)',
            )
        ];

        if ($options['isCreation']) {
            $constraints[] = new NotBlank(
                message: 'Il faut une pochette pour créer un album.',
            );
        }

        $builder
            ->add('label', null, [
                'label' => 'Titre album',
            ])
            ->add('artist', EntityType::class, [
                'class' => Artist::class,
                'choice_label' => 'label',
                'label' => 'Artiste',
            ])
            ->add('type', EnumType::class, [
                'class' => AlbumType::class,
                'label' => 'Type album',
            ])
            ->add('coverFile', FileType::class, [
                'label' => 'Pochette',
                'mapped' => false,
                'required' => $options['isCreation'],
                'constraints' => $constraints,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
            'isCreation' => true,
        ]);
    }
}