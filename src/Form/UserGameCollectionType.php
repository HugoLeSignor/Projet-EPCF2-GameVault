<?php

namespace App\Form;

use App\Entity\UserGameCollection;
use App\Enum\GameStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserGameCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => array_combine(
                    array_map(fn(GameStatus $s) => $s->label(), GameStatus::cases()),
                    GameStatus::cases(),
                ),
            ])
            ->add('note', ChoiceType::class, [
                'label' => 'Note (sur 10)',
                'choices' => array_combine(
                    array_map(fn(int $i) => "$i/10", range(1, 10)),
                    range(1, 10),
                ),
                'required' => false,
                'placeholder' => 'Non noté',
            ])
            ->add('tempsDeJeu', IntegerType::class, [
                'label' => 'Temps de jeu (en minutes)',
                'required' => false,
                'attr' => ['min' => 0, 'placeholder' => '0'],
            ])
            ->add('progression', TextareaType::class, [
                'label' => 'Où je me suis arrêté',
                'required' => false,
                'attr' => [
                    'rows' => 3,
                    'placeholder' => 'Ex: Chapitre 5, niveau 32, boss du donjon...',
                ],
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => 'Commentaire personnel',
                'required' => false,
                'attr' => ['rows' => 4],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserGameCollection::class,
        ]);
    }
}
