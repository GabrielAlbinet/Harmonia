<?php

namespace App\Form;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeletePlaylistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];

        $builder
            ->add('playlist', EntityType::class, [
                'class' => Playlist::class,
                'choice_label' => 'label',
                'label' => 'Playlist à supprimer',
                'mapped' => false,
                'query_builder' => function (PlaylistRepository $repository) use ($user) {
                    return $repository->createQueryBuilder('p')
                        ->where('p.user = :user')
                        ->setParameter('user', $user);
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
        $resolver->setRequired('user');
    }
}