<?php

namespace App\Form;

use App\Entity\UserGameCollection;
use App\Enum\GameStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserGameCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $platforms = $options['game_platforms'];
        $platformChoices = array_combine($platforms, $platforms);

        $builder
            ->add('plateforme', ChoiceType::class, [
                'label' => 'Plateforme',
                'choices' => $platformChoices,
                'required' => false,
                'placeholder' => count($platforms) > 1 ? 'Choisir une plateforme' : null,
            ])
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => array_combine(
                    array_map(fn(GameStatus $s) => $s->label(), GameStatus::cases()),
                    GameStatus::cases(),
                ),
            ])
            ->add('note', IntegerType::class, [
                'label' => 'Note (sur 10)',
                'required' => false,
                'attr' => [
                    'type' => 'range',
                    'min' => 0,
                    'max' => 10,
                    'data-optional' => 'true',
                ],
            ])
            ->add('heures', IntegerType::class, [
                'label' => 'Heures',
                'mapped' => false,
                'required' => false,
                'attr' => ['min' => 0, 'placeholder' => '0'],
            ])
            ->add('minutes', ChoiceType::class, [
                'label' => 'Minutes',
                'mapped' => false,
                'required' => false,
                'choices' => [
                    '0 min' => 0,
                    '15 min' => 15,
                    '30 min' => 30,
                    '45 min' => 45,
                ],
                'placeholder' => '0 min',
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

        // Pré-remplir heures/minutes depuis tempsDeJeu, et note (null → 0)
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $collection = $event->getData();
            $form = $event->getForm();
            if ($collection) {
                if ($collection->getTempsDeJeu() !== null) {
                    $total = $collection->getTempsDeJeu();
                    $form->get('heures')->setData(intdiv($total, 60));
                    $form->get('minutes')->setData($total % 60 >= 45 ? 45 : ($total % 60 >= 30 ? 30 : ($total % 60 >= 15 ? 15 : 0)));
                }
                if ($collection->getNote() === null) {
                    $form->get('note')->setData(0);
                }
            }
        });

        // Combiner heures + minutes → tempsDeJeu ; 0 → null pour note
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $collection = $event->getData();
            $form = $event->getForm();
            $h = (int) $form->get('heures')->getData();
            $m = (int) $form->get('minutes')->getData();
            $total = $h * 60 + $m;
            $collection->setTempsDeJeu($total > 0 ? $total : null);
            if ((int) $collection->getNote() === 0) {
                $collection->setNote(null);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserGameCollection::class,
            'game_platforms' => ['PC'],
        ]);

        $resolver->setAllowedTypes('game_platforms', 'array');
    }
}
