<?php

declare(strict_types=1);

namespace App\Infrastructure\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Repository\UncachedResponseRepository")
 */
class UncachedResponse
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
     * @ORM\Column(type="datetime")
     *
     * @var DateTimeInterface
     */
    private $createdAt;

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

    public function setCreatedAt(DateTimeInterface $createdAt) : self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt() : DateTimeInterface
    {
        return $this->createdAt;
    }
}
