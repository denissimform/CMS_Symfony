<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

class AuthenticationSubscriber implements EventSubscriberInterface
{
    // public function __construct(private UrlGeneratorInterface $urlGenerator)
    // {
    // }

    // public function onCheckPassportEvent(CheckPassportEvent $event, ExceptionEvent $exceptionEvent, Request $request): void
    public function onCheckPassportEvent(CheckPassportEvent $event): void
    {
        /** @var User $user */
        $user = $event->getPassport()->getUser();
        dd($event->getPassport());
        // if (false === $user->isIsVerified()) {
        //     $request->getSession()->set("verification_email", $user->getEmail());

        //     $urlGenerator = new UrlGeneratorInterface();
        //     $exceptionEvent->setResponse(new RedirectResponse($urlGenerator->generate("app_company_resend_verification_email")));
        // }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CheckPassportEvent::class => ['onCheckPassportEvent', -1],
        ];
    }
}
