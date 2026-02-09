<?php

namespace App\Repository;

use App\Entity\Sponsor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sponsor>
 */
class SponsorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sponsor::class);
    }

    // =========================
    // Custom queries can go here
    // =========================

    /*
    public function findBySecteur(string $secteur): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.secteur = :secteur')
            ->setParameter('secteur', $secteur)
            ->orderBy('s.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
    */
}
