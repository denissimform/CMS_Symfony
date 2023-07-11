<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterFormType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class MainController extends AbstractController
{
    #[Route('/main', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    // Registration
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, VerifyEmailHelperInterface $verifyEmailHelperInterface, MailerInterface $mailerInterface): Response
    {

        $form = $this->createForm(RegisterFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            // dd($user);
            $entityManager->persist($user);
            $entityManager->flush();

            // Create Signature
            $signatureComponents = $verifyEmailHelperInterface->generateSignature(
                'app_verify_email',
                $user->getId(),
                $user->getEmail(),
                ['id' => $user->getId()]
            );

            $name = $user->getFirstname() . ' ' . $user->getLastName();

            // Send Email
            $this->sendEmail($mailerInterface, $user->getEmail(), $signatureComponents->getSignedUrl(), $name);

            $this->addFlash('success', 'Confirm your email at : ' . $user->getEmail());

            return $this->redirectToRoute('app_login');
        }

        return $this->render('main/register.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }

    // Login
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('main/login.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'last_username' => $authenticationUtils->getLastUsername()
        ]);
    }

    // User Verification
    #[Route('/verify', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, VerifyEmailHelperInterface $verifyEmailHelperInterface, UserRepository $userRepo, EntityManagerInterface $em)
    {
        $user = $userRepo->find($request->query->get('id'));

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        try {
            $verifyEmailHelperInterface->validateEmailConfirmation(
                $request->getUri(),
                $user->getId(),
                $user->getEmail()
            );
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('error', $e->getReason());
            return $this->redirectToRoute('app_login');
        }

        $user->setIsVerified(true);
        $em->flush();

        $this->addFlash('success', 'Account Verified!! Now you can successfully log in.');
        return $this->redirectToRoute('app_login');
    }

    // Send Verification Mail
    #[Route('/sendmail', name: 'app_send_email')]
    public function sendEmail(MailerInterface $mailerInterface, string $email, string $url, string $username)
    {

        $email = (new TemplatedEmail())
            ->from('htest6236@gmail.com')
            ->to($email)
            ->subject('Email Verification')
            ->htmlTemplate('email/verify.html.twig')
            ->context([
                'confirmUrl' => $url,
                'username' => $username
            ]);

        $mailerInterface->send($email);

        return 1;
    }
}
