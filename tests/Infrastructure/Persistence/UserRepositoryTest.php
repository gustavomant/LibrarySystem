<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Src\Domain\User\User;
use Src\Infrastructure\Persistence\UserRepository;
use PDO;
use PDOStatement;

class UserRepositoryTest extends TestCase
{
    private MockObject $pdoMock;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);

        $this->userRepository = new UserRepository($this->pdoMock);
    }

    public function testCreate()
    {
        $user = new User('John Doe', 'john@example.com');

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
            ->method('execute')
            ->with([$user->getName(), $user->getEmail()])
            ->willReturn(true);

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtMock);

        $this->assertTrue($this->userRepository->create($user));
    }

    public function testFindById()
    {
        $userId = 1;
        $userData = ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'];

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
            ->method('execute')
            ->with([$userId])
            ->willReturn(true);
        $stmtMock->expects($this->once())
            ->method('fetch')
            ->willReturn($userData);

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtMock);

        $user = $this->userRepository->findById($userId);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->getName());
        $this->assertEquals('john@example.com', $user->getEmail());
    }

    public function testFindByEmail()
    {
        $email = 'john@example.com';
        $userData = ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'];

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
            ->method('execute')
            ->with([$email])
            ->willReturn(true);
        $stmtMock->expects($this->once())
            ->method('fetch')
            ->willReturn($userData);

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtMock);

        $user = $this->userRepository->findByEmail($email);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->getName());
        $this->assertEquals('john@example.com', $user->getEmail());
    }

    public function testUpdate()
    {
        $user = new User('John Doe', 'john@example.com', 1);

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
            ->method('execute')
            ->with([$user->getName(), $user->getEmail(), $user->getId()])
            ->willReturn(true);

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtMock);

        $this->assertTrue($this->userRepository->update($user));
    }

    public function testDelete()
    {
        $userId = 1;

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
            ->method('execute')
            ->with([$userId])
            ->willReturn(true);

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtMock);

        $this->assertTrue($this->userRepository->delete($userId));
    }

    public function testFindAll()
    {
        $userData = [
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'],
            ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com']
        ];

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn($userData);

        $this->pdoMock->expects($this->once())
            ->method('query')
            ->willReturn($stmtMock);

        $users = $this->userRepository->findAll();
        $this->assertCount(2, $users);
        $this->assertInstanceOf(User::class, $users[0]);
        $this->assertInstanceOf(User::class, $users[1]);
        $this->assertEquals('John Doe', $users[0]->getName());
        $this->assertEquals('Jane Smith', $users[1]->getName());
    }
}
