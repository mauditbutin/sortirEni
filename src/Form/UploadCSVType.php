<?php

namespace App\Form;

use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UploadCSVType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('submitFile', FileType::class)
            ->add('password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(
                        message: 'Please enter a password',
                    ),
                    new Length(
                        min: 6,
                        minMessage: 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        max: 4096,
                    ),
                ],
            ])
            ->add('passwordConfirmation', PasswordType::class, [
                'mapped' => false,
                'label' => 'Confirmez le mot de passe'
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'name',
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'required' => false,
            'constraints' => [
                new Callback(function ($data, $context) {  // $context est l'inspecteur de validation injecté par Symfony, il permet de naviguer dans le formulaire et de créer des erreurs
                    $form = $context->getRoot();//getRoot permet de remonter au formulaire racine (nécéssaire ici car on a des mapped false)
                    $password = $form->get('password')->getData(); // Récupère le champ password
                    $passwordConfirmation = $form->get('passwordConfirmation')->getData();// Récupère le champ passwordConfirmation

                    if ($password !== $passwordConfirmation) {
                        $context->buildViolation('Les mots de passe ne correspondent pas.')
                            ->atPath('[passwordConfirmation]')// champ auquel est rattaché le message
                            ->addViolation();//Permet de jouer le message
                    }
                }),
            ]
        ]);
    }
}
