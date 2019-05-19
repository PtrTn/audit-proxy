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
     * @var string
     * @ORM\Column(type="text")
     */
    private $response;

    /**
     * @var DateTimeInterface
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function setRequestHash(string $requestHash): self
    {
        $this->requestHash = $requestHash;

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

    public function isValid(): bool
    {
        $yesterday = new \DateTimeImmutable('1 hour ago');
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
}
