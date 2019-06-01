<?php

namespace App\Infrastructure\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Repository\UncachedResponseRepository")
 */
class UncachedResponse
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
     * @var DateTimeInterface
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

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

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getRequestBody(): string
    {
        return $this->requestBody;
    }
}
