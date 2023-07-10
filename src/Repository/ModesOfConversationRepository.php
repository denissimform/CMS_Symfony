<?php

namespace App\Repository;

use App\Entity\ModesOfConversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ModesOfConversation>
 *
 * @method ModesOfConversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModesOfConversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModesOfConversation[]    findAll()
 * @method ModesOfConversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModesOfConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModesOfConversation::class);
    }

    public function save(ModesOfConversation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ModesOfConversation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ModesOfConversation[] Returns an array of ModesOfConversation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ModesOfConversation
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
