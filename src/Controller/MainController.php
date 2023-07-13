<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterFormType;
use App\Repository\UserRepository;
// use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
    #[Route('/', name: 'app_homepage')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }

    // #[Route('/register', name: 'app_register')]
    // public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, VerifyEmailHelperInterface $verifyEmailHelperInterface, MailerInterface $mailerInterface): Response
    // {
    //     $user = new User();
    //     $form = $this->createForm(RegistrationFormType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {

    //         // encode the plain password
    //         $user->setPassword(
    //             $userPasswordHasher->hashPassword(
    //                 $user,
    //                 $form->get('plainPassword')->getData()
    //             )
    //         );

    //         $user->setUuid('hghg');

    //         $entityManager->persist($user);
    //         $entityManager->flush();
    //         // do anything else you need here, like send an email

    //         // return $userAuthenticator->authenticateUser(
    //         //     $user,
    //         //     $formLoginAuthenticator,
    //         //     $request
    //         // );

    //         // $signatureComponents = $verifyEmailHelperInterface->generateSignature(
    //         //     'app_verify_email',
    //         //     $user->getId(),
    //         //     $user->getEmail(),
    //         //     ['id' => $user->getId()]
    //         // );

    //         // dd($signatureComponents->getSignedUrl());

    //         // $this->sendEmail($mailerInterface, $user->getEmail(), $signatureComponents->getSignedUrl(), $user->getName());

    //         // $this->addFlash('success', 'Confirm your email at : ' . $user->getEmail());

    //         return $this->redirectToRoute('app_main');
    //     }

    //     return $this->render('main/register.html.twig', [
    //         'registrationForm' => $form->createView(),
    //     ]);
    // }


    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterFormType::class, $user);
        $form->handleRequest($request);

        // dd($form);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            // dd($user);
            // $user->setPassword(
            //     $userPasswordHasher->hashPassword(
            //         $user,
            //         $form->get('plainPassword')->getData()
            //     )
            // );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_main');
        }

        return $this->render('main/register.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }

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

        try {
            
            $email = (new TemplatedEmail())
                ->from('denisshingala@gmail.com')
                ->to($email)
                ->subject('Email Verification')
                ->htmlTemplate('email/verify.html.twig')
                ->context([
                    'confirmUrl' => $url,
                    'username' => $username
                ]);

            $mailerInterface->send($email);

        } catch (Exception $e) {
            return 0;
        }

        return 1;
    }
}
