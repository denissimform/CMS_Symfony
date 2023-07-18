<?php

namespace App\Controller\Company;

use App\Entity\User;
use App\Entity\Company;
use App\Form\CompanyFormType;
use App\Services\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PharIo\Manifest\InvalidEmailException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class CompanyController extends AbstractController
{
    public function __construct(private VerifyEmailHelperInterface $verifyEmail, private $email_id, private MailerInterface $mailer)
    {
    }

    #[Route("/company/register", name: "app_company_register")]
    public function registerCompany(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, EmailService $emailService): Response
    {
        try {

            $adminForm = $this->createForm(CompanyFormType::class);

            $adminForm->handleRequest($request);

            if ($adminForm->isSubmitted() && $adminForm->isValid()) {
                // create a new company
                $company = new Company();
                $company->setName($adminForm['username']->getData());
                $company->setAbout($adminForm['about']->getData());
                $company->setEstablishedAt($adminForm['establishedAt']->getData());

                // create a new user account
                $user = new User();
                $user->setUsername($adminForm['username']->getData());
                $user->setEmail($adminForm['email']->getData());
                $user->setFirstname($adminForm['firstName']->getData());
                $user->setLastName($adminForm['lastName']->getData());
                $user->setDob($adminForm['dob']->getData());
                $user->setGender($adminForm['gender']->getData());
                $user->setCompany($company);
                $user->setRoles(["ROLE_ADMIN"]);
                $user->setPassword($passwordHasher->hashPassword($user, $adminForm['password']->getData()));

                // persist the user and company
                $entityManager->persist($user);
                $entityManager->persist($company);

                // add data on database
                $entityManager->flush();

                // get singature for email verification
                $signature = $this->verifyEmail->generateSignature("app_verify_email", $user->getId(), $user->getEmail(), ["id" => $user->getId()]);

                $context = [
                    "username" => $user->getFullName(),
                    'confirmUrl' => $signature->getSignedUrl(),
                ];

                // sending email to administrator for verification
                if ($emailService->sendEmail($user->getEmail(), 'Email Verification', 'email/verify.html.twig', $context)) {
                    $this->addFlash("success", "Verification email has been sent on your email address.");
                } else {
                    throw new InvalidEmailException("Email send failed");
                }

                return $this->redirectToRoute("app_login");
            }

            return $this->render(
                'company/register.html.twig',
                [
                    "adminForm" => $adminForm->createView()
                ],
                new Response(null, $adminForm->isSubmitted() ? ($adminForm->isValid() ? 200 : 422) : 200)
            );
        } catch (Exception $err) {
            $this->addFlash("error", $err->getMessage());
            throw new Exception($err->getMessage());
        }
    }
}
