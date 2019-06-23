<?php

declare(strict_types=1);

namespace App\Infrastructure\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Response;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Repository\CachedResponseRepository")
 */
class CachedResponse
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $requestHash;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    private $requestBody;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    private $response;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTimeInterface
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTimeInterface
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTimeInterface
     */
    private $lastCacheHitAt;

    public function setId(int $id) : void
    {
        $this->id = $id;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function setRequestHash(string $requestHash) : self
    {
        $this->requestHash = $requestHash;

        return $this;
    }

    public function getRequestHash() : string
    {
        return $this->requestHash;
    }

    public function setRequestBody(string $requestBody) : self
    {
        $this->requestBody = $requestBody;

        return $this;
    }

    public function getRequestBody() : string
    {
        return $this->requestBody;
    }

    public function setResponse(string $response) : self
    {
        $this->response = $response;

        return $this;
    }

    public function getResponse() : string
    {
        return $this->response;
    }

    public function setCreatedAt(DateTimeInterface $createdAt) : self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt() : DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt) : void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getUpdatedAt() : DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setLastCacheHitAt(DateTimeInterface $lastCacheHitAt) : void
    {
        $this->lastCacheHitAt = $lastCacheHitAt;
    }

    public function getLastCacheHitAt() : DateTimeInterface
    {
        return $this->lastCacheHitAt;
    }

    public function isValid() : bool
    {
        $yesterday = new DateTimeImmutable('1 hour ago');

        return $this->createdAt > $yesterday;
    }

    public function toSymfonyResponse() : Response
    {
        return new Response(
            $this->response,
            200,
            [
                'Content-Type' => 'application/json; charset=utf-8',
                'X-Cache' => 'HIT',
            ]
        );
    }
}
