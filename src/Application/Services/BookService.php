<?php

namespace Src\Application\Services;
use Src\Domain\Book\Book;
use Src\Domain\Book\BookRepositoryInterface;
use Src\Domain\Publication\PublicationRepositoryInterface;

class BookService
{
    private BookRepositoryInterface $bookRepository;
    private PublicationRepositoryInterface $publicationRepository;

    public function __construct(
        BookRepositoryInterface $bookRepository,
        PublicationRepositoryInterface $publicationRepository
    ) {
        $this->bookRepository = $bookRepository;
        $this->publicationRepository = $publicationRepository;
    }

    public function createBook(int $publicationId): bool
    {
        $publication = $this->publicationRepository->find($publicationId);

        if ($publication === null) {
            throw new \RuntimeException('Publication not found');
        }

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
