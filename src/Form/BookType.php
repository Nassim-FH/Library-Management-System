<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 4],
            ])
            ->add('isbn', TextType::class, [
                'label' => 'ISBN',
                'attr' => ['class' => 'form-control', 'placeholder' => '978-0-123456-78-9'],
            ])
            ->add('publicationDate', DateType::class, [
                'label' => 'Publication Date',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('availableCopies', IntegerType::class, [
                'label' => 'Available Copies',
                'attr' => ['class' => 'form-control', 'min' => 0],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Category',
                'placeholder' => 'Select a category',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('authors', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'name',
                'label' => 'Authors',
                'multiple' => true,
                'expanded' => false,
                'attr' => ['class' => 'form-control'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
