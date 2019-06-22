<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\ValueObject\RequestHash;
use App\Infrastructure\Entity\UncachedResponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UncachedResponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method UncachedResponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method UncachedResponse[]    findAll()
 * @method UncachedResponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UncachedResponseRepository extends ServiceEntityRepository
{
    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        RegistryInterface $registry,
        LoggerInterface $logger
    ) {
        parent::__construct($registry, UncachedResponse::class);
        $this->logger = $logger;
    }

    /**
     * @return UncachedResponse[]
     *
     * @throws Exception
     */
    public function findMostRecent() : array
    {
        return $this->createQueryBuilder('cr')
            ->orderBy('cr.createdAt', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    public function save(UncachedResponse $uncachedResponse) : void
    {
        try {
            $this->getEntityManager()->persist($uncachedResponse);
            $this->getEntityManager()->flush();
        } catch (ORMInvalidArgumentException | ORMException $e) {
            $this->logger->error('Unable to save record to database', ['record' => $uncachedResponse]);
        }
    }

    public function delete(UncachedResponse $uncachedResponse) : void
    {
        try {
            $this->getEntityManager()->remove($uncachedResponse);
            $this->getEntityManager()->flush();
        } catch (ORMInvalidArgumentException | ORMException $e) {
            $this->logger->error('Unable to delete record from database', ['record' => $uncachedResponse]);
        }
    }

    public function hasRequestWithHash(RequestHash $hash) : bool
    {
        return $this->count(['requestHash' => (string) $hash]) > 0;
    }

    public function deleteForHash(RequestHash $hash) : void
    {
        $uncachedResponses = $this->findBy(['requestHash' => (string) $hash]);
        foreach ($uncachedResponses as $uncachedResponse) {
            $this->delete($uncachedResponse);
        }
    }
}
