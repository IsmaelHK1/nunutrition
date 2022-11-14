<?php

namespace App\Repository;

use App\Entity\Calculatrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Calculatrice>
 *
 * @method Calculatrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Calculatrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Calculatrice[]    findAll()
 * @method Calculatrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalculatriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Calculatrice::class);
    }

    public function save(Calculatrice $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Calculatrice $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Calculatrice[] Returns an array of Calculatrice objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Calculatrice
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function findOneByID($id): ?Calculatrice
   {
       return $this->createQueryBuilder('c')
           ->andWhere('c.user_id = :val')
           ->setParameter('val', $id)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }
}
