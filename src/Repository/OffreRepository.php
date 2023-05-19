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


public function filterOffre(string $destination='',float $prixMin = 0.0,?\DateTimeInterface $date = null): array
{
    $queryBuilder = $this->createQueryBuilder('o')
        ->orderBy('o.id', 'ASC');

    if ($destination != '' && $prixMin != 0) {
        $queryBuilder->leftJoin('o.id_destination', 'c')
            ->andWhere('c.pays = :destination')
            ->andWhere('o.prix_un >= :prixMin')
            ->setParameter('destination', $destination)
            ->setParameter('prixMin', $prixMin);
    } else if ($destination != '' && $prixMin==0) {
        $queryBuilder->leftJoin('o.id_destination', 'c')
            ->andWhere('c.pays like :destination')
            ->setParameter('destination', '%' . $destination . '%');
    } else if ($prixMin != 0 && $destination==='') {
        $queryBuilder->andWhere('o.prix_un >= :prixMin')
            ->setParameter('prixMin', $prixMin);
    }
    if ($date instanceof \DateTimeInterface) {
        $queryBuilder
            ->andWhere('o.date_depart >= :date')
            ->setParameter('date', $date);
    }
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
