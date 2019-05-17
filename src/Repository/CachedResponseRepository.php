<?php

namespace App\Repository;

use App\Entity\CachedResponse;
use App\ValueObject\RequestHash;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CachedResponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method CachedResponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method CachedResponse[]    findAll()
 * @method CachedResponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CachedResponseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CachedResponse::class);
    }

    public function findByRequestHash(RequestHash $hash): ?CachedResponse
    {
        try {
            return $this->createQueryBuilder('cr')
                ->andWhere('cr.requestHash = :hash')
                ->setParameter('hash', $hash->__toString())
                ->orderBy('cr.createdAt', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult()
                ;
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }
}
