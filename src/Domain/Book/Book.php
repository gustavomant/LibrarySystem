<?php

namespace Src\Domain\Book;

class Book
{
    private ?int $id;
    private int $publicationId;

    public function __construct(int $publicationId, ?int $id = null)
    {
        $this->publicationId = $publicationId;
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPublicationId(): int
    {
        return $this->publicationId;
    }
}
