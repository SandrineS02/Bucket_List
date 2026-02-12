<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Wish;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class WishFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du souhait ',
            ])
            ->add('category', EntityType::class, [
                'label' => 'Category',
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => '--Choose a category--'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du souhait ',
                'required' => false
            ])
            ->add('author', TextType::class, [
                'label' => 'Auteur du souhait ',
            ])
            ->add('published', CheckboxType::class, [
                'label' => 'Publier le souhait ?',
                'required' => false,
            ])
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '8M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ]);

        // Événement pour ajouter la checkbox "deleteImage" si une image existe déjà
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $wish = $event->getData();
            $form = $event->getForm();

            if ($wish && $wish->getFilename()) {
                // Si on modifie un wish avec une image existante, on propose une checkbox pour la supprimer
                $form->add('deleteImage', CheckboxType::class, [
                    'label' => 'Supprimer l’image ?',
                    'required' => false,
                    'mapped' => false,
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
            'attr'=> [
                'novalidate' => 'novalidate',
            ]
        ]);
    }
}
