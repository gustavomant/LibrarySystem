<?php

namespace Src\Domain\Publication;
use Src\Domain\ValueObjects\ISBN;

class Publication
{
    private int $id;
    private string $title;
    private string $author;
    private int $publishedYear;
    private ISBN $isbn;

    public function __construct(int $id, string $title, string $author, int $publishedYear, ISBN $isbn)
    {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->publishedYear = $publishedYear;
        $this->isbn = $isbn;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getPublishedYear(): int
    {
        return $this->publishedYear;
    }

    public function getIsbn(): ISBN
    {
        return $this->isbn;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    public function setPublishedYear(int $publishedYear): void
    {
        $this->publishedYear = $publishedYear;
    }

    public function setIsbn(ISBN $isbn): void
    {
        $this->isbn = $isbn;
    }
}