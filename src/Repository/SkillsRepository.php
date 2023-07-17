<?php

namespace App\Repository;

use App\Entity\Skills;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Skills>
 *
 * @method Skills|null find($id, $lockMode = null, $lockVersion = null)
 * @method Skills|null findOneBy(array $criteria, array $orderBy = null)
 * @method Skills[]    findAll()
 * @method Skills[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkillsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Skills::class);
    }

    public function save(Skills $entity, bool $flush = false): void
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
            return $queryBuilder->andWhere('u.name LIKE ?1 OR u.isActive LIKE ?1 OR u.createdAt LIKE ?1 OR u.isDeleted LIKE ?1 OR u.updatedAt LIKE ?1')
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
    public function remove(Skills $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Skills[] Returns an array of Skills objects
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

//    public function findOneBySomeField($value): ?Skills
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
