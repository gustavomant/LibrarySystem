<?php

namespace Src\Application\Services;

use Src\Domain\Book\Book;
use Src\Domain\Book\BookRepositoryInterface;

class BookService
{
    private BookRepositoryInterface $bookRepository;

    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function createBook(int $publicationId): bool
    {
        $book = new Book($publicationId);
        return $this->bookRepository->create($book);
    }

    public function getBookById(int $id): ?Book
    {
        return $this->bookRepository->find($id);
    }

    public function getAllBooks(): array
    {
        return $this->bookRepository->getAll();
    }

    public function getBooksByPublicationId(int $publicationId): array
    {
        return $this->bookRepository->findByPublicationId($publicationId);
    }

    public function updateBook(int $id, int $publicationId): bool
    {
        $book = new Book($id, $publicationId);
        return $this->bookRepository->update($id, $book);
    }

    public function deleteBook(int $id): bool
    {
        return $this->bookRepository->delete($id);
    }
}
