<?php

namespace App\Repository;

use App\Entity\FruitLegume;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FruitLegume>
 *
 * @method FruitLegume|null find($id, $lockMode = null, $lockVersion = null)
 * @method FruitLegume|null findOneBy(array $criteria, array $orderBy = null)
 * @method FruitLegume[]    findAll()
 * @method FruitLegume[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FruitLegumeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FruitLegume::class);
    }

    public function save(FruitLegume $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FruitLegume $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return FruitLegume[] Returns an array of FruitLegume objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FruitLegume
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


/**
 * Retourne les entité actifs, paginés par $page et $limit
 *
 * @param int $page 
 * @param int $limit limite des etudiants par page
 * @return FruitLegume|null
 */
   public function findByPagination($page, $limit): ?FruitLegume
   {
       return $this->createQueryBuilder('f')
           ->setFirstResult($page - 1  * $limit)
           ->setMaxResults($limit)
           ->where('f.status = \'on\'')
            ->getQuery()
            ->getResult();
   }
}
