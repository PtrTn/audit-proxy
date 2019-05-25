<?php

namespace App\Domain\ValueObject;

class RequestHash
{
    /**
     * @var string
     */
    private $hash;

    public function __construct(string $hash)
    {
        $this->hash = $hash;
    }

    public function equals(self $hash): bool
    {
        return $hash->__toString() === $this->__toString();
    }

    public function __toString(): string
    {
        return $this->hash;
    }
}
