<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Difficulty;
use App\Entity\Hike;
use App\Entity\Location;
use App\Entity\Status;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HikeCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,
                ['label' => 'Titre'])
            ->add('description', TextareaType::class, [
                'label' => 'Description et infos'
            ])
            //----------------------------------------------
            // A mettre sous forme heures minutes -> minutes
            ->add('duration', IntegerType::class, [
                'label' => 'Durée (en minutes)',
            ])
            //----------------------------------------------
            ->add('dateEvent', DateTimeType::class, [
                'label' => 'Date et heure de l\'évènement'
            ])
            ->add('dateSubscription', DateType::class, [
                'label' => 'Date limite d\'inscription'
            ])
            ->add('nbMaxSubscription', IntegerType::class, [
                'label' => 'Nombre de places'
            ])
            ->add('picture', FileType::class, [
                'label' => 'Ajouter une photo',
                'mapped' => false
            ])
//            ->add('status', EntityType::class, [
//                'class' => Status::class,
//                'choice_label' => 'id',
//            ])
            ->add('difficulty', EntityType::class, [
                'class' => Difficulty::class,
                'label' => 'Difficulté',
                'placeholder' => 'Sélectionnez un niveau',
                'choice_label' => 'label',
            ])
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'label' => 'Lieu de la randonnée',
                'placeholder' => 'Sélectionnez un lieu',
                'choice_label' => 'name',
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'label' => 'Campus',
                'choice_label' => 'name',
            ])
//            ->add('planner', EntityType::class, [
//                'class' => User::class,
//                'choice_label' => 'id',
//            ])
//            ->add('participants', EntityType::class, [
//                'class' => User::class,
//                'choice_label' => 'id',
//                'multiple' => true,
//            ])
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
