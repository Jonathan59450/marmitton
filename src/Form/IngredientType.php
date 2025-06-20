<?php

namespace App\Form;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType; // Pour les champs texte
use Symfony\Component\Form\Extension\Core\Type\MoneyType; // Pour les champs monétaires
use Symfony\Component\Form\Extension\Core\Type\SubmitType; // Pour le bouton de soumission

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control', // Classe Bootstrap
                    'minlength' => '2',
                    'maxlength' => '50'
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label mt-4' // Classe Bootstrap
                ],
                'constraints' => [ // Contraintes de validation côté formulaire (doublon avec l'entité, mais bonne pratique)
                    new \Symfony\Component\Validator\Constraints\Length(['min' => 2, 'max' => 50]),
                    new \Symfony\Component\Validator\Constraints\NotBlank()
                ]
            ])
            ->add('price', MoneyType::class, [ // Utilisation de MoneyType pour le prix
                'attr' => [
                    'class' => 'form-control', // Classe Bootstrap
                ],
                'label' => 'Prix',
                'label_attr' => [
                    'class' => 'form-label mt-4' // Classe Bootstrap
                ],
                'divisor' => 100, // Important pour MoneyType: stocke en centimes, affiche en euros
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\Positive(),
                    new \Symfony\Component\Validator\Constraints\LessThan(['value' => 200]),
                    new \Symfony\Component\Validator\Constraints\NotNull()
                ]
            ])
            ->add('submit', SubmitType::class, [ // Bouton de soumission
                'attr' => [
                    'class' => 'btn btn-primary mt-4' // Classe Bootstrap
                ],
                'label' => 'Créer mon ingrédient'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class, // Le formulaire est lié à l'entité Ingredient
        ]);
    }
}