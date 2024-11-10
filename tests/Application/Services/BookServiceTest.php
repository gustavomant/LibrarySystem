<?php

namespace Tests\Application\Services;
use PHPUnit\Framework\TestCase;
use Src\Application\Services\BookService;
use Src\Domain\Book\Book;
use Src\Domain\Book\BookRepositoryInterface;
use Src\Domain\Publication\PublicationRepositoryInterface;
use Src\Domain\Publication\Publication;

class BookServiceTest extends TestCase
{
    private BookService $bookService;
    private $bookRepositoryMock;
    private $publicationRepositoryMock;

    protected function setUp(): void
    {
        $this->bookRepositoryMock = $this->createMock(BookRepositoryInterface::class);

        $this->publicationRepositoryMock = $this->createMock(PublicationRepositoryInterface::class);

        $this->bookService = new BookService(
            $this->bookRepositoryMock,
            $this->publicationRepositoryMock
        );
    }

    public function testCreateBook(): void
    {
        $publicationId = 1;
        $book = new Book($publicationId);
        
        $publicationMock = $this->createMock(Publication::class);
        
        $this->publicationRepositoryMock->expects($this->once())
            ->method('find')
            ->with($this->equalTo($publicationId))
            ->willReturn($publicationMock);

        $this->bookRepositoryMock->expects($this->once())
            ->method('create')
            ->with($this->equalTo($book))
            ->willReturn(true);

        $result = $this->bookService->createBook($publicationId);

        $this->assertTrue($result);
    }

    public function testCreateBookFailsDueToNonExistentPublication(): void
    {
        $publicationId = 1;
        $book = new Book($publicationId);
        
        $this->publicationRepositoryMock->expects($this->once())
            ->method('find')
            ->with($this->equalTo($publicationId))
            ->willReturn(null);
    
        $this->expectException(\InvalidArgumentException::class);
    
        $this->bookService->createBook($publicationId);
    }

    public function testGetBookById(): void
    {
        $bookId = 1;
        $book = new Book($bookId);

        $this->bookRepositoryMock->expects($this->once())
            ->method('find')
            ->with($this->equalTo($bookId))
            ->willReturn($book);

        $result = $this->bookService->getBookById($bookId);
        $this->assertSame($book, $result);
    }

    public function testGetAllBooks(): void
    {
        $books = [new Book(1), new Book(2)];

        $this->bookRepositoryMock->expects($this->once())
            ->method('getAll')
            ->willReturn($books);

        $result = $this->bookService->getAllBooks();
        $this->assertSame($books, $result);
    }

    public function testGetBooksByPublicationId(): void
    {
        $publicationId = 1;
        $books = [new Book($publicationId), new Book($publicationId)];

        $this->bookRepositoryMock->expects($this->once())
            ->method('findByPublicationId')
            ->with($this->equalTo($publicationId))
            ->willReturn($books);

        $result = $this->bookService->getBooksByPublicationId($publicationId);
        $this->assertSame($books, $result);
    }

    public function testUpdateBook(): void
    {
        $bookId = 1;
        $publicationId = 2;
        $book = new Book($bookId, $publicationId);

        $this->bookRepositoryMock->expects($this->once())
            ->method('update')
            ->with($this->equalTo($bookId), $this->equalTo($book))
            ->willReturn(true);

        $result = $this->bookService->updateBook($bookId, $publicationId);
        $this->assertTrue($result);
    }

    public function testDeleteBook(): void
    {
        $bookId = 1;

        $this->bookRepositoryMock->expects($this->once())
            ->method('delete')
            ->with($this->equalTo($bookId))
            ->willReturn(true);

        $result = $this->bookService->deleteBook($bookId);
        $this->assertTrue($result);
    }
}
