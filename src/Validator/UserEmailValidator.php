<?php

namespace App\Validator;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\ConstraintValidator;

class UserEmailValidator extends ConstraintValidator
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function validate($value, Constraint $constraint)
    {
        $user = $this->userRepository->findOneBy(["email" => $value]);

        if ($user) {
            return;
        }

        if (null === $value || '' === $value) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}
