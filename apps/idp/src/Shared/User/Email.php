<?php

declare(strict_types=1);

namespace App\Shared\User;

final readonly class Email
{
    public function __construct(public string $email)
    {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(sprintf('Invalid email %s', $this->email));
        }
    }
}