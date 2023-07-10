<?php

declare(strict_types=1);

namespace App\CreateUser;

use App\Shared\Producer\KafkaProducer;
use App\Shared\Topic;
use Psr\Log\LoggerInterface;

final readonly class CreateUserHandler
{
    public function __construct(private KafkaProducer $producer, private LoggerInterface $logger)
    {
    }

    public function __invoke(CreateUserCommand $command): void
    {
        $message = sprintf(
            'User has been registered. Id: %s, email: %s, name: %s',
            $command->userId->uuid,
            $command->email->email,
            $command->name->name,
        );

        $this->logger->info($message);

        $this->producer->produce($message, Topic::USERS);
    }
}