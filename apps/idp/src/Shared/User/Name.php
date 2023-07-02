<?php

declare(strict_types=1);

namespace App\Shared\User;

final readonly class Name
{
    public function __construct(public string $name)
    {
    }
}