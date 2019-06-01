<?php

namespace App\Infrastructure\Repository;

use App\Infrastructure\Entity\CachedResponse;
use App\Domain\ValueObject\RequestHash;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CachedResponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method CachedResponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method CachedResponse[]    findAll()
 * @method CachedResponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CachedResponseRepository extends ServiceEntityRepository
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        RegistryInterface $registry,
        LoggerInterface $logger
    ) {
        parent::__construct($registry, CachedResponse::class);
        $this->logger = $logger;
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

    public function save(CachedResponse $cachedResponse) {
        try {
            $this->getEntityManager()->persist($cachedResponse);
            $this->getEntityManager()->flush();
        } catch (ORMInvalidArgumentException | ORMException $e) {
            $this->logger->error('Unable to save record to database', [
                'record' => $cachedResponse
            ]);
        }
    }
}
