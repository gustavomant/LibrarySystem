<?php

use PHPUnit\Framework\TestCase;
use Src\Domain\Book\Book;

class BookTest extends TestCase
{
    public function testBookConstructorAndGettersWithId()
    {
        $id = 1;
        $publicationId = 1001;

        $book = new Book($publicationId, $id);

        $this->assertEquals($id, $book->getId());
        $this->assertEquals($publicationId, $book->getPublicationId());
    }

    public function testBookConstructorAndGettersWithoutId()
    {
        $publicationId = 1001;

        $book = new Book($publicationId);

        $this->assertNull($book->getId());
        $this->assertEquals($publicationId, $book->getPublicationId());
    }
}
