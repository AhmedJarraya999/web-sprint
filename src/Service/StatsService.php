<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager as PersistenceObjectManager;

class StatsService
{
    private $manager;

    public function __construct(PersistenceObjectManager $manager, ManagerRegistry $managerRegistry)
    {
        #$this->manager = $manager;
        $this->manager = $managerRegistry;
    }

    public function getStats()
    {
        $users      = $this->getUsersCount();
        $ads        = $this->getAdsCount();
        $bookings   = $this->getBookingsCount();
        $comments   = $this->getCommentsCount();

        return compact('users', 'ads', 'bookings', 'comments');
    }

    public function getUsersCount()
    {
        return $this->managerRegistry->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    public function getAdsCount()
    {
        return $this->managerRegistry->createQuery('SELECT COUNT(a) FROM App\Entity\Ad a')->getSingleScalarResult();
    }

    public function getBookingsCount()
    {
        return $this->managerRegistry->createQuery('SELECT COUNT(b) FROM App\Entity\Booking b')->getSingleScalarResult();
    }

    public function getCommentsCount()
    {
        return $this->managerRegistry->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')->getSingleScalarResult();
    }

    #public function getAdsStats($direction)
    #{
    #   return $this->managerRegistry->createQuery(
    #      'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
    #     FROM App\Entity\Comment c 
    #    JOIN c.ad a
    #   JOIN a.author u
    #  GROUP BY a
    # ORDER BY note ' . $direction
    #)
    #   ->setMaxResults(5)
    #  ->getResult();
    #}
    #}
}
