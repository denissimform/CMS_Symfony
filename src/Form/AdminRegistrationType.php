<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class AdminRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['data'] ?? null;
        $isEdit = $user && $user->getId();
        
        $builder
        ->add('company', CompanyAutocompleteField::class)
            ->add('email', EmailType::class)
            ->add('username')
            ->add('firstName')
            ->add('middleName')
            ->add('lastName')
            ->add('gender', ChoiceType::class, options: [
                'choices' => User::GENDERS,
                'choice_label' => function ($choice) {
                    return $choice;
                }
            ])
            ->add('dob', options: [
                'widget' => 'single_text',
            ]);

        if (!$isEdit) {
            $builder->add('password', PasswordType::class);
        }

        if ($options['include_created_at'])
            $builder->add('createdAt', options: [
                'widget' => 'single_text',
                'disabled' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'include_created_at' => false,
        ]);
    }
}
