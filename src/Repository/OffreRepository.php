<?php

namespace App\Repository;

use App\Entity\Offre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Offre>
 *
 * @method Offre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offre[]    findAll()
 * @method Offre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offre::class);
    }

    public function save(Offre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Offre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
  
public function PaginationQuery(string $value="all"): array
{
        $queryBuilder = $this->createQueryBuilder('o')
        ->orderBy('o.id', 'ASC');

    if ($value !== "all") {
        $queryBuilder->leftJoin('o.id_destination', 'c')
            ->andWhere('c.pays = :value')
            ->setParameter('value', $value);
    }

    return $queryBuilder->getQuery()->getResult();
}
public function filterOffrePrix(float $value1, float $value2): array
{
    $queryBuilder = $this->createQueryBuilder('o')
        ->orderBy('o.id', 'ASC')
        ->andWhere('o.prix between :value1 and :value2')
        ->setParameter('value1', $value1)
        ->setParameter('value2', $value2);

    return $queryBuilder->getQuery()->getResult();
}

public function filterOffretitle(string $value): array
{
    $queryBuilder = $this->createQueryBuilder('o')
        ->orderBy('o.id', 'ASC')
        ->andWhere('o.titre like :value')
        ->setParameter('value', '%' . $value . '%');

    return $queryBuilder->getQuery()->getResult();
}

public function countOffre(): int
{
    $queryBuilder = $this->createQueryBuilder('o')
        ->select('COUNT(o.id) as offreCount');

    return $queryBuilder->getQuery()->getSingleScalarResult();
}

//    public function findOneBySomeField($value): ?Offre
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
