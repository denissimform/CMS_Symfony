<?php

namespace App\Controller;

use App\Entity\User;
use Stripe\StripeClient;
use App\Entity\Transaction;
use App\Entity\Subscription;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TransactionRepository;
use App\Repository\SubscriptionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @method User getUser()
 */
class PaymentController extends AbstractController
{
    
    public function __construct(
        private $stripe_sk,
        private EntityManagerInterface $em,
        private TransactionRepository $transactionRepository
    ) {
    }

    #[Route('/payment', name: 'app_payment')]
    public function index(SubscriptionRepository $subscriptionRepository): Response
    {
        return $this->render('payment/index.html.twig', [
            'subscriptions' => $subscriptionRepository->getGroupedSubscriptions(),
        ]);
    }

    #[Route('/payment/success', name: 'app_payment_success')]
    public function success(Request $request): Response
    {
        return $this->render('payment/success.html.twig', [
            'data' => $this->stripe_sk->checkout->sessions->retrieve(),
        ]);
    }

    #[Route('/payment/cancel', name: 'app_payment_cancel')]
    public function cancel(): Response
    {
        return $this->render('payment/cancel.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

    #[Route('/checkout/{id}', name: 'app_checkout')]
    public function checkout(Subscription $subscription): Response
    {
        $stripe = new StripeClient($this->stripe_sk);

        $company = $this->getUser()->getCompany();

        $newTransaction = new Transaction;
        $newTransaction->setCompany($company);
        $newTransaction->setSubscription($subscription);
        $newTransaction->setStatus(Transaction::STATUS['INITIATED']);
        $this->em->persist($newTransaction);
        $this->em->flush();
        
        // Generate checkout session for transaction
        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'inr',
                    'product_data' => [
                        'name' => ucfirst($subscription->getType()) . ' Subscription',
                    ],
                    'unit_amount' => $subscription->getPrice() . '00',
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('app_payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('app_payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        
        $newTransaction->setStatus(Transaction::STATUS['PENDING']);
        $newTransaction->setOrderId($checkout_session->id);
        $this->em->flush();

        return $this->redirect($checkout_session->url);
    }
}
