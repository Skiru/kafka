<?php

declare(strict_types=1);

namespace App\Shared;

use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid
{
    private function __construct(public string $uuid)
    {
    }

    public static function fromString(string $uuid): static
    {
        if (!RamseyUuid::isValid($uuid)) {
            throw new \InvalidArgumentException('Invalid uuid given');
        }

        return new static($uuid);
    }
}