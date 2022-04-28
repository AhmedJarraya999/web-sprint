<?php

namespace App\Repository;

use App\Data\SearchData2;
use App\Data\StaySearchData;
use App\Entity\Stay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Stay|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stay|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stay[]    findAll()
 * @method Stay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stay::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Stay $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Stay $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findSearch(StaySearchData $search): array
    {
        $query = $this->createQueryBuilder('s')->select('s');
        $dateSearch = ($search->getDate()) ? date_format($search->getDate(), "Y-m-d") : null;

        if ($search->getText() || $search->getDate()) { 
            $query =
                $query
                ->where('s.description LIKE :i')
                ->setParameter('i', '%' . $search->getText() . '%');
            if ($dateSearch !== null) {
                $query->andWhere('s.startdateav = :j ')
                    ->setParameter('j', $dateSearch);
            }
        }
        return $query->getQuery()->getResult();
    }



    // /**
    //  * @return Stay[] Returns an array of Stay objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Stay
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
