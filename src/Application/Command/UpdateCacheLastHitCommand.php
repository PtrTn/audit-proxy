<?php

declare(strict_types=1);

namespace App\Application\Command;

use DateTimeInterface;

class UpdateCacheLastHitCommand
{
    /** @var int */
    private $id;

    /** @var DateTimeInterface */
    private $dateTime;

    public function __construct(int $id, DateTimeInterface $dateTime)
    {
        $this->id       = $id;
        $this->dateTime = $dateTime;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getDateTime() : DateTimeInterface
    {
        return $this->dateTime;
    }
}
