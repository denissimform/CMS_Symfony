<?php

namespace App\Controller\Admin;

use App\Entity\Subscription;
use App\Form\SubscriptionType;
use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/subscriptions')]
class SubscriptionController extends AbstractController
{
    #[Route('', name: 'app_admin_subscriptions')]
    public function Subscriptions(SubscriptionRepository $subscriptionRepository): Response
    {
        return $this->render('Admin/Subscription/showsubscription.html.twig',[
            'gold' => $subscriptionRepository->findOneBy(['type'=>'gold']),
            'silver' => $subscriptionRepository->findOneBy(['type'=>'silver']),
            'premium' => $subscriptionRepository->findOneBy(['type'=>'premium']),
        ]);
    }

    #[Route('/update/{slug}', name: 'app_admin_subscription_update')]
    public function UpdateSubscription(
        EntityManagerInterface $entityManagerInterface, 
        SubscriptionRepository $subscriptionRepository,
        Request $request, 
        string $slug
    ) : Response
    {
        $subscription = new Subscription();
        $subscription = $subscriptionRepository->findOneBy(['type'=>$slug]);
        $form = $this->createForm(SubscriptionType::class, $subscription);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $subscription =  $form->getData();
            $entityManagerInterface->persist($subscription);
            $entityManagerInterface->flush();
            
            return $this->redirectToRoute('app_admin_subscriptions');
        }

        return $this->render('Admin/Subscription/update_subscription.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/update/status/{id}', name: 'app_admin_subscription_update_status')]
    public function UpdateSubscriptionStatus(
        EntityManagerInterface $entityManagerInterface, 
        Subscription $subscription
    ): Response
    {
        $subscription->setIsActive($subscription->isIsActive()^true);
        $entityManagerInterface->flush();
        return $this->redirectToRoute('app_admin_subscriptions');
    }
}
