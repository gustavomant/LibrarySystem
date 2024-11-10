<?php

namespace Src\Application\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Src\Application\Services\UserService;
use Src\Application\DTOs\UserDTO;

class UserController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function createUser(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $data = json_decode($request->getBody()->getContents(), true);
            $user = $this->userService->registerUser($data['name'], $data['email']);
            $response->getBody()->write(json_encode(['message' => 'User created successfully', 'user' => $user]));
            return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }

    public function listUsers(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $users = $this->userService->getAllUsers();
            $response->getBody()->write(json_encode($users));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function getUser(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        try {
            $user = $this->userService->getUserById((int)$args['id']);
            if ($user) {
                $userDTO = UserDTO::fromUser($user);
                $response->getBody()->write(json_encode($userDTO));
                return $response->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write(json_encode(['message' => 'User not found']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function updateUser(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        try {
            $data = json_decode($request->getBody()->getContents(), true);
            $userId = (int)$args['id'];
            $existingUser = $this->userService->getUserById($userId);

            if (!$existingUser) {
                $response->getBody()->write(json_encode(['message' => 'User not found']));
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            }

            $updatedUser = $this->userService->updateUser($existingUser, $data['name'] ?? null, $data['email'] ?? null);
            $response->getBody()->write(json_encode(['message' => 'User updated successfully', 'user' => $updatedUser]));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }

    public function deleteUser(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        try {
            $userId = (int)$args['id'];
            $deleted = $this->userService->deleteUser($userId);
            if ($deleted) {
                return $response->withStatus(204);
            }

            $response->getBody()->write(json_encode(['message' => 'User not found']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }
}
