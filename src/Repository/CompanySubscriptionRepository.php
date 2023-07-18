<?php

namespace App\Repository;

use App\Entity\CompanySubscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use PDOException;
use Symfony\Component\HttpFoundation\Response;

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

    public function changeSubscriptionStatus()
    {
        try {
            $sql = "UPDATE `company_subscription` SET `status` = :status  WHERE expires_at < current_timestamp()";
            $this->getEntityManager()->getConnection()->executeQuery($sql, ["status" => "expired"]);
        } catch (PDOException $err) {
            throw new Exception($err->getMessage());
        }
    }
}
