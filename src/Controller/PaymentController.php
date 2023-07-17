<?php

namespace App\Controller;

use App\Entity\CompanySubscription;
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
#[Route('/payment', name: 'app_payment_')]
class PaymentController extends AbstractController
{

    public function __construct(
        private $stripe_sk,
        private EntityManagerInterface $em,
        private TransactionRepository $transactionRepository
    ) {
    }

    #[Route('/', name: 'homepage')]
    public function index(SubscriptionRepository $subscriptionRepository): Response
    {
        return $this->render('payment/index.html.twig', [
            'subscriptions' => $subscriptionRepository->getOrderedSubscriptions(),
        ]);
    }

    #[Route('/response', name: 'response')]
    public function success(Request $request, TransactionRepository $transactionRepository): Response
    {
        $orderId = $request->getSession()->get('order_id');
        $stripe = new StripeClient($this->stripe_sk);

        $paymentStatus = $stripe->checkout->sessions->retrieve($orderId)->payment_status;

        switch ($paymentStatus) {
            case 'paid':
                $transactionRepository->updatePaymentStatus($orderId, Transaction::STATUS['COMPLETE']);
                break;

            case 'unpaid':
                $transactionRepository->updatePaymentStatus($orderId, Transaction::STATUS['CANCEL']);
                break;
        }

        return $this->render('payment/response.html.twig', [
            'status' => $paymentStatus,
        ]);
    }
    
    #[Route('/checkout/{id}', name: 'checkout')]
    public function checkout(Subscription $subscription, Request $request): Response
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
            'success_url' => $this->generateUrl('app_payment_response', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('app_payment_response', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        $newTransaction->setStatus(Transaction::STATUS['PENDING']);
        $newTransaction->setOrderId($checkout_session->id);
        $this->em->flush();

        $request->getSession()->set('order_id', $newTransaction->getOrderId());

        return $this->redirect($checkout_session->url);
    }
}
