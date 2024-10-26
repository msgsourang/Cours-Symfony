<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\Dette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dette>
 */
class DetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dette::class);
    }

    /**
     * Récupère les dettes d'un client spécifique.
     *
     * @param Client $client
     * @return Dette[] Retourne un tableau d'objets Dette.
     */
    public function findByClient(Client $client): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.client = :client')
            ->setParameter('client', $client)
            ->orderBy('d.date', 'ASC') // Vous pouvez ordonner par date ou tout autre critère
            ->getQuery()
            ->getResult();
    }

    // Ajoutez d'autres méthodes selon vos besoins, par exemple :

    // /**
    //  * @return Dette[] Returns an array of Dette objects
    //  */
    // public function findByExampleField($value): array
    // {
    //     return $this->createQueryBuilder('d')
    //         ->andWhere('d.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('d.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult();
    // }

    // public function findOneBySomeField($value): ?Dette
    // {
    //     return $this->createQueryBuilder('d')
    //         ->andWhere('d.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult();
    // }
}
