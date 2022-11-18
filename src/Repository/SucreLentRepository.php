<?php

namespace App\Repository;

use App\Entity\SucreLent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SucreLent>
 *
 * @method SucreLent|null find($id, $lockMode = null, $lockVersion = null)
 * @method SucreLent|null findOneBy(array $criteria, array $orderBy = null)
 * @method SucreLent[]    findAll()
 * @method SucreLent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SucreLentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SucreLent::class);
    }

    public function save(SucreLent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SucreLent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findByCat($value): array
    {
        return $this->createQueryBuilder('s')
            ->where('f.Status = \'on\'')
            ->andWhere('s.Categories = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return SucreLent[] Returns an array of SucreLent objects
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

//    public function findOneBySomeField($value): ?SucreLent
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
