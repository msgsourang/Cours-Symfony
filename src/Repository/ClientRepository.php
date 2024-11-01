<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Client;

class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function findByFilters(string $surname = '', string $telephone = '')
    {
        $qb = $this->createQueryBuilder('c');

        if (!empty($surname)) {
            $qb->andWhere('c.surname LIKE :surname')
               ->setParameter('surname', '%' . $surname . '%');
        }

        if (!empty($telephone)) {
            $qb->andWhere('c.telephone LIKE :telephone')
               ->setParameter('telephone', '%' . $telephone . '%');
        }

        return $qb->getQuery();
    }
}
