<?php

namespace Src\Infrastructure\Persistence;
use PDO;
use Src\Domain\User\User;
use Src\Domain\User\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(User $user): bool
    {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, created_at, updated_at) VALUES (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
        return $stmt->execute([$user->getName(), $user->getEmail()]);
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $this->mapRowToUser($data) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $this->mapRowToUser($data) : null;
    }

    public function update(User $user): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET name = ?, email = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        return $stmt->execute([$user->getName(), $user->getEmail(), $user->getId()]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'mapRowToUser'], $users);
    }

    private function mapRowToUser(array $data): User
    {
        return new User($data['name'], $data['email'], (int)$data['id']);
    }
}
