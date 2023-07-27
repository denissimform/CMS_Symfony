<?php

namespace App\EventSubscriber;

use App\Entity\Company;
use App\Entity\CompanySubscription;
use Symfony\Component\Routing\RouterInterface;
use App\Repository\CompanySubscriptionRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CheckSubscriptionStatus implements EventSubscriberInterface
{
    public function __construct(
        private CompanySubscriptionRepository $csr,
        private RouterInterface $router
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            LoginSuccessEvent::class => ["onLoginSuccess"]
        ];
    }

    public function onLoginSuccess(LoginSuccessEvent $event)
    {
        /** @var Company $company */
        $company = $event->getPassport()->getUser()->getCompany();

        // Find the current company subscription status
        $subscription = $this->csr->findOneBy([
            'company' => $company,
            'status' => CompanySubscription::PLAN_STATUS['CURRENT'],
        ]);

        // if not subscribed redirect to the payment page
        if (!$subscription) {
            $response = new RedirectResponse(
                $this->router->generate('app_payment_homepage')
            );

            return $event->setResponse($response);
        }

        // Redirect to the dashboard
        return $event->setResponse(new RedirectResponse($this->router->generate('app_admin_homepage')));
    }
}
