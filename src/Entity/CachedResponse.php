<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Response;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CachedResponseRepository")
 */
class CachedResponse
{
    /**
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
     * @var Response
     * @ORM\Column(type="object")
     */
    private $response;

    /**
     * @var DateTimeInterface
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequestHash(): ?string
    {
        return $this->requestHash;
    }

    public function setRequestHash(string $requestHash): self
    {
        $this->requestHash = $requestHash;

        return $this;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function setResponse(Response $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isValid(): bool
    {
        // Todo, implement time based validation.
        return true;
    }

    public function toSymfonyResponse(): Response
    {
        return $this->response;
    }
}
