<?php

namespace App\Form;

use App\Entity\Department;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class DepartmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a Name of Department',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Name of Department should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ]
            ])
            ->add('description', TextareaType::class ,[
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a description of Department',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Please enter a description at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Department::class,
        ]);
    }
}
