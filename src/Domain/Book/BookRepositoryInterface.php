<?php

namespace Src\Domain\Book;

interface BookRepositoryInterface
{
    public function create(Book $book): bool;
    public function find(int $id): ?Book;
    public function getAll(): array;
    public function findByPublicationId(int $publicationId): array;
    public function update(int $id, Book $book): bool;
    public function delete(int $id): bool;
}
