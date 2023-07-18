<?php

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 *
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function save(Transaction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Transaction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function updatePaymentStatus(string $orderId, string $status): ?bool
    {
        return $this->createQueryBuilder('t')
            ->update()
            ->set('t.status', ':status')
            ->Where('t.orderId = :orderId')
            ->setParameter('status', $status)
            ->setParameter('orderId', $orderId)
            ->getQuery()
            ->execute();
    }


    public function dynamicDataAjaxVise(int $limit, int $start, string $orderByField, string $orderDirection, string $searchBy): array
    {
        $queryBuilder = $this->createQueryBuilder('transaction')
            ->innerJoin('transaction.company', 'company')
            ->innerJoin('transaction.subscription', 'subscription')
            ->select(['transaction.id', 'transaction.status', 'transaction.orderId', 'company.name', 'subscription.type']);

           switch ($orderByField) {
            case 'name':
                $queryBuilder->orderBy("company.$orderByField", $orderDirection);
                break;

            case 'type':
                $queryBuilder->orderBy("subscription.$orderByField", $orderDirection);
                break; 
            
            default:
                $queryBuilder->orderBy("transaction.$orderByField", $orderDirection);
                break;
           }   

        if ($searchBy){
            return $queryBuilder->andWhere('transaction.status LIKE ?1 OR transaction.orderId LIKE ?1 OR company.name LIKE ?1 OR subscription.type LIKE ?1')
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
    //     * @return Transaction[] Returns an array of Transaction objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

}