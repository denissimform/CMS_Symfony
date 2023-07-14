<?php

namespace App\Controller\Company;

use App\Form\UserFormType;
use App\Entity\User;
use App\Entity\Company;
use App\Repository\UserRepository;
use App\Security\UserCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PharIo\Manifest\InvalidEmailException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class CompanyController extends AbstractController
{
    public function __construct(private VerifyEmailHelperInterface $verifyEmail, private $email_id, private MailerInterface $mailer)
    {
    }

    #[Route("/company/register", name: "app_company_register")]
    public function registerCompany(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        try {

            $adminForm = $this->createForm(UserFormType::class);

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
                // dd($adminForm['password']->getData());
                // persist the user and company
                $entityManager->persist($user);
                $entityManager->persist($company);

                // add data on database
                $entityManager->flush();

                // get singature for email verification
                $signature = $this->verifyEmail->generateSignature("app_compnay_verify_email", $user->getId(), $user->getEmail(), ["id" => $user->getId()]);

                // get full name of administrator
                $fullName = $user->getFirstname() . " " . $user->getLastname();

                // sending email to administrator for verification
                if ($this->sendEmail($user->getEmail(), $fullName, $signature->getSignedUrl())) {
                    $this->addFlash("success", "Verification email has been sent on your email address.");
                } else {
                    throw new InvalidEmailException("Email send failed");
                }

                return $this->redirectToRoute("app_login");
            }
            
            return $this->render(
                'company/register.html.twig',
                ["adminForm" => $adminForm->createView()],
                new Response(null, $adminForm->isSubmitted() ? ($adminForm->isValid() ? 200 : 422) : 200)
            );
        } catch (Exception $err) {
            $this->addFlash("error", $err->getMessage());
            throw new Exception($err->getMessage());
        }
    }

    // send email
    private function sendEmail($email, $username, $url): bool
    {
        try {
            $email = (new TemplatedEmail())
                ->from($this->email_id)
                ->to($email)
                ->subject("Email verification")
                ->htmlTemplate('email/verify.html.twig')
                ->context([
                    'confirmUrl' => $url,
                    'username' => $username
                ]);

            $this->mailer->send($email);

            return true;
        } catch (Exception $err) {
            $this->addFlash("error", $err->getMessage());
            return false;
        }
    }

    // verify email address
    #[Route("/company/verify-email", name: "app_compnay_verify_email")]
    public function verifyAdminEmail(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, UserAuthenticatorInterface $userAuthenticator, UserCustomAuthenticator $userCustomAuthenticator): Response
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
            $entityManager->flush();

            // set success flash message
            $this->addFlash("success", "Email has been verified!, now you can login on your account.");
        } catch (VerifyEmailExceptionInterface $err) {

            // set error flash message
            $this->addFlash("error", $err->getMessage());
        }

        $userAuthenticator->authenticateUser($user, $userCustomAuthenticator, $request);

        return $this->redirectToRoute("app_homepage");
    }
}
