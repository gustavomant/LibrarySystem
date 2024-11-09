<?php

namespace Src\Application\DTOs;
use Src\Domain\User\User;

class UserDTO
{
    private $id;
    private $name;
    private $email;

    public function __construct(int $id = null, string $name, string $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    public static function fromUser(User $user): self
    {
        return new self(
            $user->getId(),
            $user->getName(),
            $user->getEmail()
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
