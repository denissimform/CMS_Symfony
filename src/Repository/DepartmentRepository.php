<?php

namespace App\Repository;

use App\Entity\Department;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Department>
 *
 * @method Department|null find($id, $lockMode = null, $lockVersion = null)
 * @method Department|null findOneBy(array $criteria, array $orderBy = null)
 * @method Department[]    findAll()
 * @method Department[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepartmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Department::class);
    }

    public function save(Department $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function dynamicDataAjaxVise(int $limit, int $start, string $orderByField, string $orderDirection, string $searchBy): array
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->orderBy("u.$orderByField", $orderDirection);

        if ($searchBy){
            return $queryBuilder->andWhere('u.name LIKE ?1 OR u.description LIKE ?1 OR u.isActive LIKE ?1 OR u.createdAt LIKE ?1 OR u.isDeleted LIKE ?1 OR u.updatedAt LIKE ?1')
            ->setParameter(1, '%' . $searchBy . '%')
            ->getQuery()
            ->getResult();
        }

        return $queryBuilder->setMaxResults($limit)
            ->setFirstResult($start)
            ->andWhere('u.isDeleted = :val')
            ->setParameter('val', false)
            ->getQuery()
            ->getResult();
    }
    public function getTotalUsersCount(): int
    {
        return count(
            $this->createQueryBuilder('u')
                ->andWhere('u.isDeleted = :val')
                ->setParameter('val', false)
                ->getQuery()
                ->getResult()
        );
    }

    public function remove(Department $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Department[] Returns an array of Department objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Department
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
