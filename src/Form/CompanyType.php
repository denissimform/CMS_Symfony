<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                "constraints" => [
                    new NotBlank(message: "Please enter a name"),
                ]
            ])
            ->add('about', CKEditorType::class, [
                "constraints" => [
                    new NotBlank(message: "Please enter a description")
                ]
            ])
            ->add('establishedAt', null, [
                "constraints" => [
                    new NotBlank(message: "Please enter a date"),
                ],
                "widget" => "single_text",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
