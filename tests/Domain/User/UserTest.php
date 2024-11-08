<?php

use PHPUnit\Framework\TestCase;
use Src\Domain\User\User;
use InvalidArgumentException;

class UserTest extends TestCase
{
    public function testUserConstructorAndGettersWithId()
    {
        $id = 1;
        $name = "John Doe";
        $email = "john@example.com";

        $user = new User($name, $email, $id);

        $this->assertEquals($id, $user->getId());
        $this->assertEquals($name, $user->getName());
        $this->assertEquals($email, $user->getEmail());
    }

    public function testUserConstructorAndGettersWithoutId()
    {
        $name = "Jane Doe";
        $email = "jane@example.com";

        $user = new User($name, $email);

        $this->assertNull($user->getId());
        $this->assertEquals($name, $user->getName());
        $this->assertEquals($email, $user->getEmail());
    }

    public function testSetters()
    {
        $user = new User("John Doe", "john@example.com");

        $user->setName("Jane Doe");
        $user->setEmail("jane@example.com");

        $this->assertEquals("Jane Doe", $user->getName());
        $this->assertEquals("jane@example.com", $user->getEmail());
    }
}
