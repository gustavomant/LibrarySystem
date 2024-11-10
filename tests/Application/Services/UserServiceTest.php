<?php

namespace Tests\Application\Services;

use PHPUnit\Framework\TestCase;
use Src\Application\DTOS\UserDTO;
use Src\Application\Services\UserService;
use Src\Domain\User\User;
use Src\Domain\User\UserRepositoryInterface;
use Exception;

class UserServiceTest extends TestCase
{
    private UserService $userService;
    private $userRepositoryMock;

    protected function setUp(): void
    {
        $this->userRepositoryMock = $this->createMock(UserRepositoryInterface::class);
        $this->userService = new UserService($this->userRepositoryMock);
    }

    public function testRegisterUserSuccess(): void
    {
        $name = 'John Doe';
        $email = 'john.doe@example.com';
        $user = new User($name, $email);

        $this->userRepositoryMock->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn(null);

        $this->userRepositoryMock->expects($this->once())
            ->method('create')
            ->with($user)
            ->willReturn(true);

        $userInstance = $this->userService->registerUser($name, $email);
        $this->assertInstanceOf(UserDTO::class, $userInstance);
        $this->assertEquals($name, $userInstance->getName());
        $this->assertEquals($email, $userInstance->getEmail());
    }

    public function testRegisterUserEmailAlreadyExists(): void
    {
        $name = 'John Doe';
        $email = 'john.doe@example.com';

        $this->userRepositoryMock->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn(new User($name, $email));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("User with this email already exists.");

        $this->userService->registerUser($name, $email);
    }

    public function testUpdateUserSuccess(): void
    {
        $userId = 1;
        $name = 'John Updated';
        $email = 'john.updated@example.com';
        $user = new User('John Doe', 'john.doe@example.com', $userId);

        $this->userRepositoryMock->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn(null);

        $this->userRepositoryMock->expects($this->once())
            ->method('update')
            ->with($user)
            ->willReturn(true);

        $userInstance = $this->userService->updateUser($user, $name, $email);
        $this->assertInstanceOf(UserDTO::class, $userInstance);
        $this->assertEquals($name, $userInstance->getName());
        $this->assertEquals($email, $userInstance->getEmail());
    }

    public function testUpdateUserEmailAlreadyInUse(): void
    {
        $userId = 1;
        $user = new User('John Doe', 'john.doe@example.com', $userId);

        $this->userRepositoryMock->expects($this->once())
            ->method('findByEmail')
            ->with('john.updated@example.com')
            ->willReturn(new User('Jane Doe', 'john.updated@example.com'));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Email is already in use by another user.");

        $this->userService->updateUser($user, 'John Updated', 'john.updated@example.com');
    }

    public function testDeleteUserSuccess(): void
    {
        $userId = 1;
        $user = new User('John Doe', 'john.doe@example.com', $userId);

        $this->userRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($userId)
            ->willReturn($user);

        $this->userRepositoryMock->expects($this->once())
            ->method('delete')
            ->with($userId)
            ->willReturn(true);

        $result = $this->userService->deleteUser($userId);
        $this->assertTrue($result);
    }

    public function testDeleteUserNotFound(): void
    {
        $userId = 1;

        $this->userRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($userId)
            ->willReturn(null);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("User not found.");

        $this->userService->deleteUser($userId);
    }

    public function testGetUserByIdSuccess(): void
    {
        $userId = 1;
        $user = new User('John Doe', 'john.doe@example.com', $userId);

        $this->userRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($userId)
            ->willReturn($user);

        $userInstance = $this->userService->getUserById($userId);
        $this->assertInstanceOf(User::class, $userInstance);
        $this->assertEquals($userId, $userInstance->getId());
    }

    public function testGetUserByIdNotFound(): void
    {
        $userId = 1;

        $this->userRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($userId)
            ->willReturn(null);

        $userInstance = $this->userService->getUserById($userId);
        $this->assertNull($userInstance);
    }

    public function testGetUserByEmailSuccess(): void
    {
        $email = 'john.doe@example.com';
        $user = new User('John Doe', $email);

        $this->userRepositoryMock->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn($user);

        $userInstance = $this->userService->getUserByEmail($email);
        $this->assertInstanceOf(User::class, $userInstance);
        $this->assertEquals($email, $userInstance->getEmail());
    }

    public function testGetUserByEmailNotFound(): void
    {
        $email = 'john.doe@example.com';

        $this->userRepositoryMock->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn(null);

        $userInstance = $this->userService->getUserByEmail($email);
        $this->assertNull($userInstance);
    }

    public function testGetAllUsers(): void
    {
        $users = [
            new User('John Doe', 'john.doe@example.com'),
            new User('Jane Doe', 'jane.doe@example.com')
        ];

        $this->userRepositoryMock->expects($this->once())
            ->method('findAll')
            ->willReturn($users);

        $userInstances = $this->userService->getAllUsers();
        $this->assertCount(2, $userInstances);
        $this->assertInstanceOf(UserDTO::class, $userInstances[0]);
    }
}
