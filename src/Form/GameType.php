<?php

namespace App\Form;

use App\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image',
                'required' => false,
                'allow_delete' => true,
                'download_uri' => false,
            ])
            ->add('genre', ChoiceType::class, [
                'label' => 'Genre',
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
                'placeholder' => 'Choisir un genre',
            ])
            ->add('plateforme', ChoiceType::class, [
                'label' => 'Plateforme',
                'choices' => [
                    'PC' => 'PC',
                    'PlayStation 5' => 'PS5',
                    'PlayStation 4' => 'PS4',
                    'Xbox Series X/S' => 'Xbox Series',
                    'Xbox One' => 'Xbox One',
                    'Nintendo Switch' => 'Switch',
                    'Nintendo Switch 2' => 'Switch 2',
                ],
                'placeholder' => 'Choisir une plateforme',
            ])
            ->add('dateDeSortie', DateType::class, [
                'label' => 'Date de sortie',
                'widget' => 'single_text',
                'required' => false,
                'input' => 'datetime_immutable',
            ])
            ->add('developpeur', TextType::class, [
                'label' => 'Développeur',
                'required' => false,
            ])
            ->add('editeur', TextType::class, [
                'label' => 'Éditeur',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
