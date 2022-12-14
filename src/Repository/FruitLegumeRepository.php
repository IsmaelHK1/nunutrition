<?php

namespace App\Repository;

use App\Entity\FruitLegume;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
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

    public function findByCat($value): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.Status = \'on\'')
            ->andWhere('f.Categories = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
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
 * Retourne les entit?? actifs, pagin??s par $page et $limit
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

   /**
 * Retourne les entit?? actifs, pagin??s par $page et $limit
 *
 * @param string $cat 
 * @param int $page 
 * @param int $limitCal limite de calories
 * @return Array|null
 */
public function findIDByCat($cat, $page, $limitCal, EntityManager $entityManager): ?Array
{

    return $entityManager->getRepository(FruitLegume::class)->createQueryBuilder('f')
        ->setMaxResults(1)
        ->where('f.status = \'on\'')
        ->andWhere('f.categories = ' . $cat)
        ->getQuery()
        ->getResult();
}
}