<?php

namespace App\Form;

use App\Filter\ProduitFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add("min", TextType::class, [
                "required" => false
            ])

            ->add("max", TextType::class, [
                "required" => false
            ])

            ->add("recherche", TextType::class, [
                "required" => false
            ])

            ->add("order", ChoiceType::class, [
                "required" => false,
                "choices" => [
                    "Prix Croissant" => 1,
                    "Prix Décroissant" => 2,
                    "Titre Croissant" => 3,
                    "Titre Décroissant" => 4,
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProduitFilter::class
        ]);
    }
}
