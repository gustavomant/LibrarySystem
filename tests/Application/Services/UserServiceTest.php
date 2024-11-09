<?php

namespace Tests\Application\Services;

use PHPUnit\Framework\TestCase;
use Src\Application\Services\UserService;
use Src\Domain\User\User;
use Src\Domain\User\UserRepositoryInterface;
use Src\Application\DTOs\UserDTO;
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

        $userDTO = $this->userService->registerUser($name, $email);
        $this->assertInstanceOf(UserDTO::class, $userDTO);
        $this->assertEquals($name, $userDTO->getName());
        $this->assertEquals($email, $userDTO->getEmail());
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

        $userDTO = $this->userService->updateUser($user, $name, $email);
        $this->assertInstanceOf(UserDTO::class, $userDTO);
        $this->assertEquals($name, $userDTO->getName());
        $this->assertEquals($email, $userDTO->getEmail());
    }

    public function testUpdateUserEmailAlreadyInUse(): void
    {
        $userId = 1;
        $user = new User('John Doe', 'john.doe@example.com', $userId);

        $this->userRepositoryMock->expects($this->once())
            ->method('findByEmail')
            ->with('john.updated@example.com')
            ->willReturn(new User('Jane Doe', 'john.updated@example.com')); // Email already in use by another user

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

        $userDTO = $this->userService->getUserById($userId);
        $this->assertInstanceOf(UserDTO::class, $userDTO);
        $this->assertEquals($userId, $userDTO->getId());
    }

    public function testGetUserByIdNotFound(): void
    {
        $userId = 1;

        $this->userRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($userId)
            ->willReturn(null);

        $userDTO = $this->userService->getUserById($userId);
        $this->assertNull($userDTO);
    }

    public function testGetUserByEmailSuccess(): void
    {
        $email = 'john.doe@example.com';
        $user = new User('John Doe', $email);

        $this->userRepositoryMock->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn($user);

        $userDTO = $this->userService->getUserByEmail($email);
        $this->assertInstanceOf(UserDTO::class, $userDTO);
        $this->assertEquals($email, $userDTO->getEmail());
    }

    public function testGetUserByEmailNotFound(): void
    {
        $email = 'john.doe@example.com';

        $this->userRepositoryMock->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn(null);

        $userDTO = $this->userService->getUserByEmail($email);
        $this->assertNull($userDTO);
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

        $userDTOs = $this->userService->getAllUsers();
        $this->assertCount(2, $userDTOs);
        $this->assertInstanceOf(UserDTO::class, $userDTOs[0]);
    }
}
