<?php

declare(strict_types=1);

namespace App\Shared\User;

final readonly class User
{
    public function __construct(
        public UserId $id,
        public Email  $email,
        public Name   $name,
    )
    {
    }
}