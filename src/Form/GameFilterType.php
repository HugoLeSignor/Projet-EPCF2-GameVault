<?php

namespace App\Form;

use App\Enum\GameStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search', SearchType::class, [
                'label' => 'Rechercher',
                'required' => false,
                'attr' => ['placeholder' => 'Nom du jeu...'],
            ])
            ->add('plateforme', ChoiceType::class, [
                'label' => 'Plateforme',
                'required' => false,
                'placeholder' => 'Toutes',
                'choices' => [
                    'PC' => 'PC',
                    'PlayStation 5' => 'PS5',
                    'PlayStation 4' => 'PS4',
                    'Xbox Series X/S' => 'Xbox Series',
                    'Xbox One' => 'Xbox One',
                    'Nintendo Switch' => 'Switch',
                    'Nintendo Switch 2' => 'Switch 2',
                ],
            ])
            ->add('genre', ChoiceType::class, [
                'label' => 'Genre',
                'required' => false,
                'placeholder' => 'Tous',
                'choices' => [
                    'Action' => 'Action',
                    'Aventure' => 'Aventure',
                    'RPG' => 'RPG',
                    'FPS' => 'FPS',
                    'Stratégie' => 'Stratégie',
                    'Sport' => 'Sport',
                    'Course' => 'Course',
                    'Simulation' => 'Simulation',
                    'Puzzle' => 'Puzzle',
                    'Plateforme' => 'Plateforme',
                    'Horreur' => 'Horreur',
                    'Indie' => 'Indie',
                ],
            ]);

        if ($options['show_status_filter']) {
            $builder->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'required' => false,
                'placeholder' => 'Tous',
                'choices' => array_combine(
                    array_map(fn(GameStatus $s) => $s->label(), GameStatus::cases()),
                    array_map(fn(GameStatus $s) => $s->value, GameStatus::cases()),
                ),
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
            'show_status_filter' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
