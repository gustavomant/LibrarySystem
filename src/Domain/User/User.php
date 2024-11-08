<?php

namespace Src\Domain\User;

use InvalidArgumentException;

class User
{
    private $id;
    private $name;
    private $email;

    public function __construct(string $name, string $email, int $id = null)
    {
        if (empty($name) || empty($email)) {
            throw new InvalidArgumentException('Name and email cannot be empty.');
        }

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

    public function setName($name) {
        $this->name = $name;
    }

    public function setEmail($email) {
        $this->email = $email;
    }
}
