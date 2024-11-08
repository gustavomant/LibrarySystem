<?php

namespace Src\Domain\Book;

class Book
{
    private int $id;
    private int $publicationId;

    public function __construct(int $id, int $publicationId)
    {
        $this->id = $id;
        $this->publicationId = $publicationId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPublicationId(): int
    {
        return $this->publicationId;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setPublicationId(int $publicationId): void
    {
        $this->publicationId = $publicationId;
    }
}
