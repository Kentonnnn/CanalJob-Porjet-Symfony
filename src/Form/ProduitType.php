<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                "label" => "Titre du produit",
                "required" => false,
                "attr" => [
                    "placeholder" => "Saisir le titre",
                ],
                "label_attr" => [
                    'id' => "blabla"
                ],

                "row_attr" => [
                    "id" => "bizarre"
                ],
            ])

            ->add('description', TextareaType::class, [
                "required" => false,
                "attr" => [
                    "rows" => 8,
                    "placeholder" => "Saisir une description",
                ],
                
            ])

            ->add('prix', MoneyType::class, [
                "currency" => "EUR",
                "attr" => [
                    "placeholder" => "Saisir un prix",
                ],
            ])

            // ->add("categorie", EntityType::class, [ 
            //     "class" => Categorie::class, 
            //     "choice_label" => "nom",
            //     "placeholder" => "Saisir une catégorie",
            //     "required" => FALSE
            // ])
        ;


        if($options['ajouter'])
            {
                $builder->add('image', FileType::class, [
                    "required" => false,
                    "mapped" => false,
                    "attr" => [
                        "onchange" => "loadFile(event)"
                    ]
                ]);
            
            } 
            elseif($options['modifier'])
            {
                $builder->add('imageUpdate', FileType::class, [
                    "required" => false,
                    "mapped" => false,
                    "attr" => [
                        "onchange" => "loadFile(event)"
                    ]
                ]);
            }
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
            'ajouter' => false,
            'modifier' => false
        ]);
    }
}
