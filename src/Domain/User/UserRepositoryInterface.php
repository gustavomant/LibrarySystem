<?php

namespace Src\Domain\User;

interface UserRepositoryInterface
{
    public function create(User $user): bool;
    public function findById(int $id): ?User;
    public function findByEmail(string $email): ?User;
    public function update(User $user): bool;
    public function delete(int $id): bool;
    public function findAll(): array;
}