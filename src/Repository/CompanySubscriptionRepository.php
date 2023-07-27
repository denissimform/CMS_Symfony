<?php

namespace App\Repository;

use App\Entity\CompanySubscription;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<CompanySubscription>
 *
 * @method CompanySubscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanySubscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanySubscription[]    findAll()
 * @method CompanySubscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanySubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompanySubscription::class);
    }

    public function save(CompanySubscription $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CompanySubscription $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getSubscriptionExpiredCompanies(): mixed
    {
        return $this->createQueryBuilder('cs')
            ->where('cs.status = :status')
            ->andWhere('cs.expiresAt < :currentTime')
            ->setParameters([
                'status' => CompanySubscription::PLAN_STATUS['CURRENT'],
                'currentTime' => date('Y-m-d H:i:s')
            ])
            ->getQuery()
            ->getResult();
    }

    public function changeSubscriptionStatus(): bool
    {
        return $this->createQueryBuilder('cs')
            ->update()
            ->set('cs.status', ':status')
            ->where('cs.expiresAt < :currentTime')
            ->setParameters([
                'status' => CompanySubscription::PLAN_STATUS['EXPIRED'],
                'currentTime' => date('Y-m-d H:i:s')
            ])
            ->getQuery()
            ->execute();
    }
}
