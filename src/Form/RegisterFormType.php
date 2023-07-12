<?php

namespace App\Form;

use App\Entity\User;
use PHPUnit\Framework\Constraint\IsTrue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', null, [
                'constraints' => [new NotBlank(message: 'Username cannot be blank.')],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [new NotBlank(message: 'Email cannot be blank.')],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm Password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('firstName', null, [
                'constraints' => [new NotBlank(message: 'FirstName cannot be blank.'), new Regex(
                    pattern: '/\d/',
                    match: false,
                    message: 'First name cannot contain a number',
                )],
            ])
            ->add('lastName', null, [
                'constraints' => [new NotBlank(message: 'LastName cannot be blank.'), new Regex(
                    pattern: '/\d/',
                    match: false,
                    message: 'Last name cannot contain a number',
                )],
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => array(
                    'Male' => 'Male',
                    'Female' => 'Female',
                    'Others' => 'Others'
                ),
                'expanded' => false,
                'constraints' => [new NotBlank(message: 'Please select gender.')],
            ])
            ->add('dob', BirthdayType::class, [
                'label' => 'Date of Birth',
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                ],
                'widget' => 'single_text'
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'I agree to terms and conditions.',
                'constraints' => [
                    new IsTrue(message: 'Please agree to Terms & Conditions to move further.')
                ]
            ])
            ->add('company', CompanyAutocompleteField::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
