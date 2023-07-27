<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;
use App\Entity\CompanySubscription;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;

#[AsEntityListener(
    event: Events::postUpdate,
    method: 'postUpdate',
    entity: CompanySubscription::class
)]
class UnsubscribeCompany
{
    public function __construct(
        private EntityManagerInterface $em,
        private LoggerInterface $logger
    ) {
    }

    public function postUpdate(CompanySubscription $cs): void
    {
        if ($cs->getStatus() === CompanySubscription::PLAN_STATUS['EXPIRED']) {
            try {
                /** @var User $companyAdmin */
                $companyAdmin = $this->em->getRepository(User::class)->findAdmin($cs->getCompany()->getId());
    
                // remove access to the portal for the company admin
                $companyAdmin->removeRole("FEATURE_ACCESS");
                $this->em->persist($companyAdmin);
                $this->em->flush();
            } catch (\Exception $e) {
                $this->logger->info(sprintf('Unable to update user roles. Error: %s', $e->getMessage()));
            }

            $this->logger->info(sprintf('Persisted successfully and updated user roles!'));
        }
    }
}