<?php

namespace App\Repository;

use App\Entity\SubscriptionDuration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SubscriptionDuration>
 *
 * @method SubscriptionDuration|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubscriptionDuration|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubscriptionDuration[]    findAll()
 * @method SubscriptionDuration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscriptionDurationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubscriptionDuration::class);
    }


    public function dynamicDataAjaxVise(int $limit, int $start, string $orderByField, string $orderDirection, string $searchBy): array
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->innerJoin('u.subscriptionId', 'subscription')
            ->select(['u.id', 'u.duration', 'u.price', 'subscription.type', 'u.isActive', 'subscription.criteria_dept', 'subscription.criteria_user', 'subscription.criteria_storage']);

           switch ($orderByField) {
            case 'type':
                $queryBuilder->orderBy("subscription.$orderByField", $orderDirection);
                break; 
            
            default:
                $queryBuilder->orderBy("u.$orderByField", $orderDirection);
                break;
           }   

        if ($searchBy){
            return $queryBuilder->andWhere('u.duration LIKE ?1 OR u.price LIKE ?1 OR subscription.type LIKE ?1')
            ->setParameter(1, '%' . $searchBy . '%')
            ->getQuery()
            ->getResult();
        }

        return $queryBuilder->setMaxResults($limit)
            ->setFirstResult($start)
            ->getQuery()
            ->getResult();
    }

    public function getTotalUsersCount(): int
    {
        return count(
            $this->createQueryBuilder('u')
                ->getQuery()
                ->getResult()
        );
    }

//    /**
//     * @return SubscriptionDuration[] Returns an array of SubscriptionDuration objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SubscriptionDuration
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
