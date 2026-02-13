<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Wish;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Commentaire',
            ])
            ->add('score', NumberType::class, [
                'label' => 'Note (entre 0 et 5)',
            ])
            ->add('dateCreated', null, [
                'label' => 'Commentaire crée le ',
            ])
            ->add('DateUpdated', null, [
                'label' => 'Commentaire modifié le ',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'label' => 'Utilisateur',
                'choice_label' => 'username',
            ])
            ->add('wish', EntityType::class, [
                'class' => Wish::class,
                'label' => 'Souhait',
                'choice_label' => 'title',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
