<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('category', ChoiceType::class, [
    'choices' => [
        'Science-Fiction' => 'Science-Fiction',
        'Mystery' => 'Mystery',
        'Autobiography' => 'Autobiography',
    ],
    'placeholder' => 'Choisir une catégorie', // ✅ option utile
    'required' => true, // ✅ obligatoire
])

            ->add('author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => fn(Author $a) => $a->getFirstName().' '.$a->getLastName(),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
