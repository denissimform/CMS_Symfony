<?php

namespace App\EventListener;

use DateTime;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\Events;
use App\Entity\Transaction;
use Psr\Log\LoggerInterface;
use App\Entity\CompanySubscription;
use App\Repository\CompanySubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;

#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Transaction::class)]
class SubscribeClient
{
    public function __construct(
        private EntityManagerInterface $em,
        private LoggerInterface $logger
    ) {
    }

    public function postUpdate(Transaction $transaction): void
    {
        if ($transaction->getStatus() === Transaction::STATUS['COMPLETE']) {
            // check if the user has already subscribed before or not.
            $olderSubscription = $this->em->getRepository(CompanySubscription::class)->findOneBy([
                'company' => $transaction->getCompany()->getId(),
                'status' => CompanySubscription::PLAN_STATUS['CURRENT']
            ]);

            // get the subscription duration for this subscription
            $duration = $transaction->getSubscription()->getDuration();

            $subscribe = new CompanySubscription();
            // calculate expiry date for given record
            $expiryDate = new DateTimeImmutable(
                date(
                    "Y-m-d H:i:s",
                    strtotime(
                        ($olderSubscription
                            ? $olderSubscription->getExpiresAt()->format('Y-m-d H:i:s')
                            : $transaction->getCreatedAt()->format('Y-m-d H:i:s')
                        )
                            .  " + $duration months"
                    )
                )
            );
            $subscribe->setExpiresAt($expiryDate);
            $subscribe->setStatus(
                $olderSubscription
                    ? CompanySubscription::PLAN_STATUS['UPCOMING']  // if already subscribed
                    : CompanySubscription::PLAN_STATUS['CURRENT']   // if new subscription
            );
            $subscribe->setsubscription($transaction->getSubscription());
            $subscribe->setcompany($transaction->getCompany());

            /** @var User $user */
            $user = $this->em->getRepository(User::class)->findOneBy(['company' => $transaction->getCompany()->getId()]);
            $user->addRoles(['FEATURE_ACCESS']);

            try {
                // persist the subscription entry in database
                $this->em->persist($subscribe);
                $this->em->persist($user);
                $this->em->flush();
            } catch (\Exception $e) {
                $this->logger->info(sprintf('Unable to persist subscription. Error: %s', $e->getMessage()));
            }

            $this->logger->info(sprintf('Persisted successfully and updated user roles!'));
        }
    }
}
