<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Src\Infrastructure\Persistence\PublicationRepository;
use Src\Domain\Publication\Publication;
use Src\Domain\ValueObjects\ISBN;

class PublicationRepositoryTest extends TestCase
{
    private $mockDb;
    private $repository;

    protected function setUp(): void
    {
        $this->mockDb = $this->createMock(PDO::class);
        $this->repository = new PublicationRepository($this->mockDb);
    }

    public function testCreate()
    {
        $publication = new Publication(
            'Test Title',
            'Test Author',
            2023,
            new ISBN('9783161484100')
        );

        $mockStmt = $this->createMock(PDOStatement::class);
        $mockStmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->mockDb->expects($this->once())
            ->method('prepare')
            ->willReturn($mockStmt);

        $result = $this->repository->create($publication);
        $this->assertTrue($result);
    }

    public function testGetAll()
    {
        $rows = [
            [
                'id' => 1,
                'isbn' => '9783161484100',
                'title' => 'Test Title 1',
                'author' => 'Test Author 1',
                'published_year' => 2023,
            ],
            [
                'id' => 2,
                'isbn' => '9781234567890',
                'title' => 'Test Title 2',
                'author' => 'Test Author 2',
                'published_year' => 2022,
            ],
        ];

        $mockStmt = $this->createMock(PDOStatement::class);
        $mockStmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn($rows);

        $this->mockDb->expects($this->once())
            ->method('query')
            ->willReturn($mockStmt);

        $publications = $this->repository->getAll();

        $this->assertCount(2, $publications);
        $this->assertInstanceOf(Publication::class, $publications[0]);
    }

    public function testFind()
    {
        $row = [
            'id' => 1,
            'isbn' => '9783161484100',
            'title' => 'Test Title',
            'author' => 'Test Author',
            'published_year' => 2023,
        ];

        $mockStmt = $this->createMock(PDOStatement::class);
        $mockStmt->expects($this->once())
            ->method('fetch')
            ->willReturn($row);

        $this->mockDb->expects($this->once())
            ->method('prepare')
            ->willReturn($mockStmt);

        $publication = $this->repository->find(1);
        $this->assertInstanceOf(Publication::class, $publication);
        $this->assertEquals('Test Title', $publication->getTitle());
    }

    public function testUpdate()
    {
        $publication = new Publication(
            'Updated Title',
            'Updated Author',
            2024,
            new ISBN('9783161484111')
        );

        $mockStmt = $this->createMock(PDOStatement::class);
        $mockStmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->mockDb->expects($this->once())
            ->method('prepare')
            ->willReturn($mockStmt);

        $result = $this->repository->update(1, $publication);
        $this->assertTrue($result);
    }

    public function testDelete()
    {
        $mockStmt = $this->createMock(PDOStatement::class);
        $mockStmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->mockDb->expects($this->once())
            ->method('prepare')
            ->willReturn($mockStmt);

        $result = $this->repository->delete(1);
        $this->assertTrue($result);
    }
}
