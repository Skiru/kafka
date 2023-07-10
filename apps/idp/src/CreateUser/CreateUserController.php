<?php

declare(strict_types=1);

namespace App\CreateUser;

use App\Shared\User\Email;
use App\Shared\User\Name;
use App\Shared\User\UserId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CreateUserController extends AbstractController
{
    public function create(Request $request, CreateUserHandler $handler): JsonResponse
    {
        $request = json_decode($request->getContent(), true);

        $command = new CreateUserCommand(
            UserId::fromString($request['id']),
            new Email($request['email']),
            new Name($request['name']),
        );

        $handler($command);

        return new JsonResponse(status: Response::HTTP_CREATED);
    }
}