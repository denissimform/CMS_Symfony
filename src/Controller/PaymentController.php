<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Company;
use Stripe\StripeClient;
use App\Entity\Transaction;
use App\Entity\Subscription;
use Psr\Log\LoggerInterface;
use App\Service\UpdatePaymentStatus;
use App\Service\UserSubscriptionChecker;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SubscriptionRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

/**
 * @method User getUser()
 */
#[IsGranted('ROLE_USER')]
#[Route('/payment', name: 'app_payment_')]
class PaymentController extends AbstractController
{
    public function __construct(
        private $stripe_sk,
        private EntityManagerInterface $em,
        private LoggerInterface $logger,
        private Security $security
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
    public function success(Request $request, UpdatePaymentStatus $ups): Response
    {
        // get stored order id from the session
        $orderId = $request->getSession()->get('order_id');

        // redirect to homepage if order id is not found
        if (!$orderId) {
            $this->addFlash('error', 'Your transaction is on hold. Please wait for some time or contact support');
            return $this->redirectToRoute('app_admin_homepage');
        }

        // create instance of Stripe Object
        $stripe = new StripeClient($this->stripe_sk);

        // retrieve the checkout session object from Stripe for particular order id
        $stripeObj = $stripe->checkout->sessions->retrieve($orderId);
        $paymentStatus = $stripeObj->payment_status;
        $paymentAmount = $stripeObj->amount_subtotal / 100;

        // update the payment status
        switch ($paymentStatus) {
            case 'paid':
                $ups->updateStatus($orderId, Transaction::STATUS['COMPLETE'], $paymentAmount);
                break;

            case 'unpaid':
                $ups->updateStatus($orderId, Transaction::STATUS['CANCEL'], $paymentAmount);
                break;
        }

        // render the response page
        return $this->render('payment/response.html.twig', [
            'status' => $paymentStatus,
        ]);
    }

    #[Route('/checkout/{id}', name: 'checkout')]
    public function checkout(Subscription $subscription, Request $request, UserSubscriptionChecker $usc): Response
    {
        // get current logged in User and Company
        /** @var Company $company */
        $company = $this->getUser()->getCompany();
        
        // check if user is already subscribed
        if($usc->isAlreadySubscribed($company->getId())){
            $this->addFlash('success', 'You have already subscribed to the plan');
            return $this->redirectToRoute('app_admin_homepage');
        }

        // create instance of Stripe Object
        $stripe = new StripeClient($this->stripe_sk);

        // throw exception if something wents wrong
        if (!$company)
            throw new UserNotFoundException(message: 'company not found!');

        // create new transaction object
        $newTransaction = new Transaction;
        $newTransaction->setCompany($company);
        $newTransaction->setSubscription($subscription);
        $newTransaction->setStatus(Transaction::STATUS['INITIATED']);      // Set initial status for new transaction

        try {
            // persist and store the transaction
            $this->em->persist($newTransaction);
            $this->em->flush();
        } catch (\Exception $e) {
            $this->logger->info(sprintf('Unable to persist new transaction. Error: %s', $e->getMessage()));
        }
        
        // generate checkout session for transaction
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
        
        // update transaction status as the checkout session is created for the same
        $newTransaction->setStatus(Transaction::STATUS['PENDING']);
        $newTransaction->setOrderId($checkout_session->id);

        try {
            // persist and update the transaction
            $this->em->persist($newTransaction);
            $this->em->flush();

            // save the order id in session for future retrieval
            $request->getSession()->set('order_id', $newTransaction->getOrderId());
        } catch (\Exception $e) {
            $this->logger->info(sprintf('Unable to update new transaction. Error: %s', $e->getMessage()));
        }

        // redirect to Stripe Payment Gateway
        return $this->redirect($checkout_session->url);
    }
}
