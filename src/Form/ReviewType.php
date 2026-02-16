<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note', ChoiceType::class, [
                'label' => 'Note',
                'choices' => array_combine(
                    array_map(fn(int $i) => "$i/10", range(1, 10)),
                    range(1, 10),
                ),
                'expanded' => true,
                'placeholder' => false,
            ])
            ->add('contenu', TextareaType::class, [
                'label' => 'Votre avis',
                'attr' => [
                    'rows' => 6,
                    'placeholder' => 'Partagez votre expérience avec ce jeu (minimum 10 caractères)...',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
