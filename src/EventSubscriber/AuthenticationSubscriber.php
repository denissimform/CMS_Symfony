<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Security\AccountNotVerifiedException;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;

class AuthenticationSubscriber implements EventSubscriberInterface
{
    public function __construct(private RouterInterface $router, private Security $security)
    {
    }

    // execute after check passport authentication
    public function onCheckPassportEvent(CheckPassportEvent $event): void
    {
        $passport = $event->getPassport();

        if (!$passport instanceof Passport) {
            throw new Exception("Unexpected Passport instance");
        }

        /** @var User $user */
        $user = $event->getPassport()->getUser();
        
        if (null === $user->isIsVerified()) {
            throw new AccountNotVerifiedException();
        }
        
        if (!$user instanceof UserInterface) {
            throw new Exception("Unexpected user type!");
        }
    }

    // On login failure event occur
    public function onLoginFailureEvent(LoginFailureEvent $event)
    {
        if (!$event->getException() instanceof AccountNotVerifiedException) {
            return;
        }

        // set email on session
        $event->getRequest()->getSession()->set("verification_email", $event->getPassport()->getUser()->getUserIdentifier());

        // create redirect response
        $resposne = new RedirectResponse($this->router->generate("app_resend_verification_email"));

        // set response headers
        $event->setResponse($resposne);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CheckPassportEvent::class => ['onCheckPassportEvent', -1],
            LoginFailureEvent::class => ['onLoginFailureEvent', -1]
        ];
    }
}
