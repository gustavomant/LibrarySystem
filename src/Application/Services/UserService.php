<?php

namespace Src\Application\Services;

use Src\Domain\User\User;
use Src\Domain\User\UserRepositoryInterface;
use Src\Application\DTOs\UserDTO;
use Exception;

class UserService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerUser(string $name, string $email): UserDTO
    {
        if ($this->userRepository->findByEmail($email)) {
            throw new Exception("User with this email already exists.");
        }

        $user = new User($name, $email);
        $this->userRepository->create($user);

        return UserDTO::fromUser($user);
    }

    public function updateUser(User $user, ?string $name = null, ?string $email = null): UserDTO
    {
        if ($name !== null) {
            $user->setName($name);
        }

        if ($email !== null) {
            $existingUser = $this->userRepository->findByEmail($email);
            if ($existingUser && $existingUser->getId() !== $user->getId()) {
                throw new Exception("Email is already in use by another user.");
            }
            $user->setEmail($email);
        }

        $this->userRepository->update($user);

        return UserDTO::fromUser($user);
    }

    public function deleteUser(int $userId): bool
    {
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            throw new Exception("User not found.");
        }

        return $this->userRepository->delete($userId);
    }

    public function getUserById(int $userId): ?User
    {
        return $this->userRepository->findById($userId);
    }

    public function getUserByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    public function getAllUsers(): array
    {
        $users = $this->userRepository->findAll();
        return array_map(fn(User $user) => UserDTO::fromUser($user), $users);
    }
}
