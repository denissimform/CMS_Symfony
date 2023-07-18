<?php

namespace App\Form;

use App\Validator\UserEmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ForgotPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                "label" => "Email address",
                "constraints" => [
                    new NotBlank(message: "Please enter your email address"),
                    new Email(message: "Please enter valid email address"),
                    new UserEmail()
                ]
            ]);
    }
}
