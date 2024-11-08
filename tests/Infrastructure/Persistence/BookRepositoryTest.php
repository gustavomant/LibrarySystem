<?php

use PHPUnit\Framework\TestCase;
use PDO;
use Src\Domain\Book\Book;
use Src\Infrastructure\Persistence\BookRepository;

class BookRepositoryTest extends TestCase
{
    private $pdoMock;
    private $bookRepository;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);

        $this->bookRepository = new BookRepository($this->pdoMock);
    }

    public function testCreateBook()
    {
        $book = new Book(1, 1);

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtMock);

        $this->assertTrue($this->bookRepository->create($book));
    }

    public function testFindBookById()
    {
        $data = [
            'id' => 1,
            'publication_id' => 1,
        ];

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmtMock->expects($this->once())
            ->method('fetch')
            ->willReturn($data);

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtMock);

        $book = $this->bookRepository->find(1);
        
        $this->assertInstanceOf(Book::class, $book);
        $this->assertEquals(1, $book->getId());
        $this->assertEquals(1, $book->getPublicationId());
    }

    public function testGetAllBooks()
    {
        $data = [
            ['id' => 1, 'publication_id' => 1],
            ['id' => 2, 'publication_id' => 2]
        ];

        $stmtMock = $this->createMock(PDOStatement::class);

        $stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn($data);

        $pdoMock = $this->createMock(PDO::class);

        $pdoMock->expects($this->once())
            ->method('query')
            ->willReturn($stmtMock);

        $bookRepository = new BookRepository($pdoMock);

        $books = $bookRepository->getAll();

        $this->assertCount(2, $books);
        $this->assertInstanceOf(Book::class, $books[0]);
        $this->assertEquals(1, $books[0]->getPublicationId());
        $this->assertEquals(2, $books[1]->getPublicationId());
    }


    public function testFindByPublicationId()
    {
        $data = [
            ['id' => 1, 'publication_id' => 1],
            ['id' => 2, 'publication_id' => 1]
        ];

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn($data);

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtMock);

        $books = $this->bookRepository->findByPublicationId(1);

        $this->assertCount(2, $books);
        $this->assertInstanceOf(Book::class, $books[0]);
    }

    public function testUpdateBook()
    {
        $book = new Book(1, 1);

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtMock);

        $this->assertTrue($this->bookRepository->update(1, $book));
    }

    public function testDeleteBook()
    {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtMock);

        $this->assertTrue($this->bookRepository->delete(1));
    }
}
