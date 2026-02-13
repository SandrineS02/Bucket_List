<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    // construit le formulaire
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            // plainPassword = mot de passe en clair, il est hashé puis mit dans password
            ->add('plainPassword', PasswordType::class, [
                // plainPassword n'existe pas dans l'entité
                'mapped' => false,
                // pour que le navigateur ne remplisse pas automatiquement
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci d'entrer votre mot de passe",
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au minimum 6 caractères',
                        // taille max prévue dans symfony)
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    // configure le formulaire
    public function configureOptions(OptionsResolver $resolver): void
    {
        // indique que le formulaire est lié à l'entity User
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
