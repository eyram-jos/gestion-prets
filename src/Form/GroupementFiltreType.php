<?php

namespace App\Form;

use App\Dto\GroupementFiltreDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// Formulaire de filtre de la liste des groupements, branché sur le DTO d'entrée.
class GroupementFiltreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tel', TextType::class, [
                'label' => 'Téléphone',
                'required' => false,
                'attr' => ['placeholder' => 'ex. 77 100'],
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'required' => false,
                'attr' => ['placeholder' => 'ex. Dakar'],
            ])
            ->add('rechercher', SubmitType::class, ['label' => 'Rechercher']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GroupementFiltreDto::class,
            // Un filtre se transmet dans l'adresse (?tel=...&ville=...) : on peut le partager et paginer.
            'method' => 'GET',
            'csrf_protection' => false,
            // Le paramètre page de l'adresse n'est pas un champ du formulaire : on l'accepte.
            'allow_extra_fields' => true,
        ]);
    }

    // Nom vide : les paramètres de l'adresse s'appellent simplement tel et ville.
    public function getBlockPrefix(): string
    {
        return '';
    }
}