<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Difficulty;
use App\Entity\Hike;
use App\Entity\Location;
use App\Entity\Status;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class HikeCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('dateEvent', DateTimeType::class)
            ->add('dateSubscription', DateType::class)
            ->add('nbMaxSubscription', IntegerType::class)
            ->add('difficulty', EntityType::class, [
                'class' => Difficulty::class,
                'placeholder' => 'Sélectionnez un niveau',
                'choice_label' => 'label',
            ])
            ->add('durationHours', ChoiceType::class, [
                'mapped' => false,
                'placeholder' => 'heures',
                'choices' => [
                    '00' => 0,
                    '01' => 1,
                    '02' => 2,
                    '03' => 3,
                    '04' => 4,
                    '05' => 5,
                    '06' => 6,
                    '07' => 7,
                    '08' => 8,
                    '09' => 9,
                    '10' => 10
                ],
            ])
            ->add('durationMinutes', ChoiceType::class, [
                'mapped' => false,
                'placeholder' => 'minutes',
                'choices' => [
                    '00' => 0,
                    '15' => 15,
                    '30' => 30,
                    '45' => 45,
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'Entrez une durée'),
                ],
            ])
            ->add('description', TextareaType::class)
            ->add('picture', FileType::class, [
                'mapped' => false
            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'placeholder' => 'Sélectionnez une ville',
                'choice_label' => 'name',
                'mapped' => false
            ])
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'placeholder' => 'Sélectionnez un lieu',
                'choice_label' => 'name',
//                'choices' => []
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'name',
            ])
            ->add('create', SubmitType::class, [
                'label' => 'Créer',
            ])
            ->add('publish', SubmitType::class, [
                'label' => 'Publier',
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hike::class,
            'required' => false,
        ]);
    }
}
