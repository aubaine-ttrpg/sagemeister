<?php

namespace App\Form;

use App\Entity\AbilityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AbilityTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name of the Ability Type',
                'required' => true
            ])
            ->add('color', TextType::class,  [
                'label' => 'Color of the Ability Type',
                'required' => true
            ])
            ->add('icon', TextType::class,  [
                'label' => 'Icon of the Ability Type',
                'required' => true
            ])
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AbilityType::class,
        ]);
    }
}
