<?php

namespace Src\Infrastructure\Persistence;

use PDO;
use Src\Domain\Book\Book;
use Src\Domain\Book\BookRepositoryInterface;

class BookRepository implements BookRepositoryInterface
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(Book $book): bool
    {
        $stmt = $this->db->prepare("INSERT INTO books (publication_id) VALUES (:publication_id)");
        $stmt->bindValue(':publication_id', $book->getPublicationId(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function find(int $id): ?Book
    {
        $stmt = $this->db->prepare("SELECT * FROM books WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? new Book($row['publication_id'], $row['id']) : null;
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM books");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => new Book($row['publication_id'], $row['id']), $result);
    }

    public function findByPublicationId(int $publicationId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM books WHERE publication_id = :publication_id");
        $stmt->bindValue(':publication_id', $publicationId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => new Book($row['publication_id'], $row['id']), $result);
    }

    public function update(int $id, Book $book): bool
    {
        $stmt = $this->db->prepare("UPDATE books SET publication_id = :publication_id WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':publication_id', $book->getPublicationId(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM books WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
