<?php

namespace App\Repository;

use App\Entity\FruitSec;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FruitSec>
 *
 * @method FruitSec|null find($id, $lockMode = null, $lockVersion = null)
 * @method FruitSec|null findOneBy(array $criteria, array $orderBy = null)
 * @method FruitSec[]    findAll()
 * @method FruitSec[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FruitSecRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FruitSec::class);
    }

    public function save(FruitSec $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FruitSec $entity, bool $flush = false): void
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
//     * @return FruitSec[] Returns an array of FruitSec objects
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

//    public function findOneBySomeField($value): ?FruitSec
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
