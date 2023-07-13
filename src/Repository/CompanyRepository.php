<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Company>
 *
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    public function save(Company $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Company $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

        // Chart Data
        // public function getData(): array
        // {
        //     return $this->createQueryBuilder('u')
        //         ->select('count(u.id) as Count', 'u.isActive')
        //         ->groupBy('u.isActive')
        //         ->getQuery()
        //         ->getResult();
        // }

    //    /**
    //     * @return Company[] Returns an array of Company objects
    //     */
    // For Charts
    // public function findByActiveStatus($val): array
    // {
    //     return $this->createQueryBuilder('c')
    //         ->select('count(c.id)')
    //         ->andWhere('c.isActive = :val')
    //         ->setParameter('val', $val)
    //         ->getQuery()
    //         ->getResult();
    // }

    //    public function findOneBySomeField($value): ?Company
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    public function dynamicDataAjaxVise(int $limit, int $start, string $orderByField, string $orderDirection, string $searchBy): array
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->orderBy("u.$orderByField", $orderDirection);

        if ($searchBy){
            return $queryBuilder->andWhere('u.name LIKE ?1 OR u.about LIKE ?1 OR u.establishedAt LIKE ?1 OR u.isActive LIKE ?1')
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
}
