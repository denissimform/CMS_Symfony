<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\EmployeeFormType;
use App\Service\EmailService;
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

class UserController extends AbstractController
{
    public function __construct(private VerifyEmailHelperInterface $verifyEmail, private $email_id, private MailerInterface $mailer)
    {
    }

    #[Route("/user/register", name: "app_user_register")]
    public function registerCompany(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, EmailService $emailService): Response
    {
        try {
            $form = $this->createForm(EmployeeFormType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var User $user */
                $user = $form->getData();
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );

                $entityManager->persist($user);
                $entityManager->flush();

                // get singature for email verification
                $signatureComponents = $this->verifyEmail->generateSignature('app_verify_email', $user->getId(), $user->getEmail(), ['id' => $user->getId()]);

                $context = [
                    "username" => $user->getFullName(),
                    'confirmUrl' => $signatureComponents->getSignedUrl(),
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
                'main/register.html.twig',
                [
                    'userForm' => $form->createView(),
                ],
                new Response(null, $form->isSubmitted() ? ($form->isValid() ? 200 : 422) : 200)
            );
        } catch (Exception $err) {

            // set flash message
            $this->addFlash("error", $err->getMessage());
            throw new Exception($err->getMessage());
        }
    }
}
