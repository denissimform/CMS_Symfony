<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use App\Form\ForgotPasswordFormType;
use App\Form\ResetPasswordFormType;
use App\Services\EmailService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Security\UserCustomAuthenticator;
use PharIo\Manifest\InvalidEmailException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class MainController extends AbstractController
{
    public function __construct(private VerifyEmailHelperInterface $verifyEmail, private EntityManagerInterface $entityManager)
    {
    }

    /** 
     * app homepage
     *
     * @return Response
     */
    #[Route('/', name: 'app_homepage')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }

    /** 
     * rendering login form
     *
     * @return Response
     */
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        /** @var User $user  */
        $user = $this->getUser();

        if ($user) {
            switch (true) {
                case $user->isSuperAdmin():
                    return $this->redirectToRoute("app_sa_homepage");
                case $user->isAdmin():
                    return $this->redirectToRoute("app_admin_homepage");
                default:
                    return $this->redirectToRoute("app_homepage");
            }
        }
        return $this->render('main/login.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'last_username' => $authenticationUtils->getLastUsername()
        ]);
    }

    /** 
     * logout page
     *
     * @return Response
     */
    #[Route('/logout', name: 'app_logout')]
    public function logout(): Response
    {
        throw new \Exception("You can't login again!");
    }

    /** 
     * verify email action
     *
     * @return Response
     */
    #[Route("/verify-email", name: "app_verify_email")]
    public function verifyAdminEmail(Request $request, UserRepository $userRepository, UserAuthenticatorInterface $userAuthenticator, UserCustomAuthenticator $userCustomAuthenticator): Response
    {
        try {
            $user = $userRepository->find($request->query->get('id'));
            if (!$user) {
                throw new UserNotFoundException("User doesn't exist!");
            }
            $this->verifyEmail->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());

            // set flag true for email confirmation
            $user->setIsVerified(true);

            // change on database 
            $this->entityManager->flush();

            // set success flash message
            $this->addFlash("success", "Email has been verified!, now you can login on your account.");

            // automatically authenticate user
            $userAuthenticator->authenticateUser($user, $userCustomAuthenticator, $request);
        } catch (VerifyEmailExceptionInterface $err) {

            // set error flash message
            $this->addFlash("error", $err->getMessage());
        }


        return $this->redirectToRoute("app_homepage");
    }


    /** 
     * resent verification email action
     *
     * @return Response
     */
    #[Route("/resend-verify-email", name: "app_resend_verify_email")]
    public function resendVerifyEmail(Request $request, UserRepository $userRepository, EmailService $emailService): Response
    {
        try {
            // get verification email id from sesison
            $email = $request->getSession()->get("verification_email");

            // if not found then throw an error
            if (!$email)
                throw new NotFoundHttpException("Invalid request!");

            $user = $userRepository->findOneBy(["email" => $email]);

            // if user is not found then throw an error
            if (null === $user)
                throw new UserNotFoundException("User doesn't exist!");

            // generate signature
            $signature = $this->verifyEmail->generateSignature("app_verify_email", $user->getId(), $user->getEmail(), ["id" => $user->getId()]);

            $context = [
                "username" => $user->getFullName(),
                'confirmUrl' => $signature->getSignedUrl(),
            ];

            // send signature to the user mail id
            if ($emailService->sendEmail($user->getEmail(), 'Email Verification', 'email/verify.html.twig', $context)) {

                // clear session data
                $request->getSession()->clear();

                $this->addFlash("success", "Verification email has been sent on your email address.");
            } else {
                $this->addFlash("error", "Somthing went wrong during sent mail.");
                throw new InvalidEmailException("Email send failed");
            }
        } catch (Exception $err) {
            $this->addFlash("error", $err->getMessage());
            throw new Exception($err->getMessage());
        }

        return $this->redirectToRoute("app_login");
    }

    /** 
     * rendering resent verification email
     *
     * @return Response
     */
    #[Route("/resend-verification-email", name: "app_resend_verification_email")]
    public function resendVerificationEmail(Request $request): Response
    {
        try {
            if (null === $request->getSession()->get("verification_email")) {
                return $this->redirectToRoute("app_login");
            }

            return $this->render("company/resend_verification_email.html.twig");
        } catch (Exception $err) {
            throw new Exception($err->getMessage());
        }
    }

    /** 
     * verify forget password email
     * and handle the reset password form
     *
     * @return Response
     */
    #[Route("/verify-forgot-password", name: "app_verify_forgot_password_email")]
    public function verifyForgotPassword(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, UserAuthenticatorInterface $userAuthenticator, UserCustomAuthenticator $userCustomAuthenticator): Response
    {
        try {
            // validate uri
            if (null === $request->query->get('token') || null === $request->query->get('signature') || null === $request->query->get('token') || null === $request->query->get('id')) {
                throw new NotAcceptableHttpException("Invalid request!");
            }

            // create form
            $form = $this->createForm(ResetPasswordFormType::class);

            $form->handleRequest($request);
            // validate form request
            if ($form->isSubmitted() && $form->isValid()) {
                $user = $userRepository->find($request->query->get('id'));

                // if user not found
                if (!$user) {
                    throw new UserNotFoundException("User not exists!");
                }

                // verify email token
                $this->verifyEmail->validateEmailConfirmation($request->getUri(), $request->query->get('id'), $user->getEmail());

                // set new password
                $user->setPassword($passwordHasher->hashPassword($user, $form['Password']->getData()));

                $this->entityManager->flush();

                // automatically authenticate user
                $userAuthenticator->authenticateUser($user, $userCustomAuthenticator, $request);
                
                return $this->redirectToRoute('app_login');
            }

            // render form
            return $this->render(
                "main/reset_password.html.twig",
                ["form" => $form->createView()],
                new Response(null, $form->isSubmitted() ? ($form->isValid() ? 200 : 422) : 200)
            );
        } catch (Exception $err) {
            $this->addFlash("error", $err->getMessage());
        }

        // if error occurred
        return $this->redirectToRoute("app_login");
    }

    /** 
     * rendering forget password form
     *
     * @return Response
     */
    #[Route("/forgot-password", name: "app_forgot_password")]
    public function forgotPassword(Request $request, EmailService $emailService, UserRepository $userRepository): Response
    {
        try {
            $form = $this->createForm(ForgotPasswordFormType::class);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $user = $userRepository->findOneBy(["email" => $form['email']->getData()]);

                // get signature
                $signature = $this->verifyEmail->generateSignature("app_verify_forgot_password_email", $user->getId(), $user->getEmail(), ["id" => $user->getId()]);

                // create context
                $context = ["confirmUrl" => $signature->getSignedUrl(), "username" => $user->getFullName()];

                if ($emailService->sendEmail($user->getEmail(), "Reset Password", 'email/verify.html.twig', $context)) {
                    $this->addFlash("success", "Reset password mail has been sent on your mail id!");
                } else {
                    $this->addFlash("error", "Somthing went wring during sending email");
                }

                return $this->redirectToRoute("app_login");
            }

            $response = new Response(null, ($form->isSubmitted() ? ($form->isValid() ? 200 : 422) : 200));
        } catch (Exception $err) {
            $this->addFlash("error", $err->getMessage());

            // server side error exception
            $response = new Response(null, 500);
        }

        return $this->render(
            "main/forgot_password.html.twig",
            ["form" => $form->createView()],
            $response
        );
    }
}
