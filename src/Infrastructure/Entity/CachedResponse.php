<?php

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
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $requestHash;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $requestBody;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $response;

    /**
     * @var DateTimeInterface
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var DateTimeInterface
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @var DateTimeInterface
     * @ORM\Column(type="datetime")
     */
    private $lastCacheHitAt;

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setRequestHash(string $requestHash): self
    {
        $this->requestHash = $requestHash;

        return $this;
    }

    public function setRequestBody(string $requestBody): self
    {
        $this->requestBody = $requestBody;

        return $this;
    }

    public function setResponse(string $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function setLastCacheHitAt(DateTimeInterface $lastCacheHitAt): void
    {
        $this->lastCacheHitAt = $lastCacheHitAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function isValid(): bool
    {
        $yesterday = new DateTimeImmutable('1 hour ago');
        return $this->createdAt > $yesterday;
    }

    public function toSymfonyResponse(): Response
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

    public function getRequestBody(): string
    {
        return $this->requestBody;
    }
}
