<?php

namespace App\Validator;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueEmailValidator extends ConstraintValidator
{
    private $userRepository;

    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        $user = $this->userRepository->findOneBy(["email" => $value]);

        if (!$user) {
            return;
        }

        /* @var App\Validator\UniqueEmail $constraint */
        if (null === $value || '' === $value) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }

}
