<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterFormType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
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
}
