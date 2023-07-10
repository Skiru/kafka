<?php

declare(strict_types=1);

namespace App\CreateUser;

use App\Shared\User\Email;
use App\Shared\User\Name;
use App\Shared\User\UserId;

final readonly class CreateUserCommand
{
    public function __construct(
        public UserId $userId,
        public Email  $email,
        public Name   $name,
    )
    {
    }
}