<?php

namespace Src\Domain\User;

use InvalidArgumentException;

class User
{
    private ?int $id;
    private string $name;
    private string $email;

    public function __construct(string $name, string $email, ?int $id = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->id = $id;
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

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
