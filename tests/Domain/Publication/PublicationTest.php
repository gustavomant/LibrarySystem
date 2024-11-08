<?php

use PHPUnit\Framework\TestCase;
use Src\Domain\Publication\Publication;
use Src\Domain\ValueObjects\ISBN;

class PublicationTest extends TestCase
{
    public function testPublicationConstructorAndGettersWithId()
    {
        $title = "Test Title";
        $author = "Test Author";
        $publishedYear = 2022;
        $isbn = new ISBN("9783161484100");
        $id = 1;

        $publication = new Publication($title, $author, $publishedYear, $isbn, $id);

        $this->assertEquals($id, $publication->getId());
        $this->assertEquals($title, $publication->getTitle());
        $this->assertEquals($author, $publication->getAuthor());
        $this->assertEquals($publishedYear, $publication->getPublishedYear());
        $this->assertEquals($isbn, $publication->getIsbn());
    }

    public function testPublicationConstructorAndGettersWithoutId()
    {
        $title = "Test Title";
        $author = "Test Author";
        $publishedYear = 2022;
        $isbn = new ISBN("9783161484100");

        $publication = new Publication($title, $author, $publishedYear, $isbn);

        $this->assertNull($publication->getId());
        $this->assertEquals($title, $publication->getTitle());
        $this->assertEquals($author, $publication->getAuthor());
        $this->assertEquals($publishedYear, $publication->getPublishedYear());
        $this->assertEquals($isbn, $publication->getIsbn());
    }

    public function testSetters()
    {
        $publication = new Publication("Initial Title", "Initial Author", 2021, new ISBN("9783161484100"));

        $publication->setTitle("Updated Title");
        $publication->setAuthor("Updated Author");
        $publication->setPublishedYear(2023);
        $updatedIsbn = new ISBN("9781234567897");
        $publication->setIsbn($updatedIsbn);

        $this->assertEquals("Updated Title", $publication->getTitle());
        $this->assertEquals("Updated Author", $publication->getAuthor());
        $this->assertEquals(2023, $publication->getPublishedYear());
        $this->assertEquals($updatedIsbn, $publication->getIsbn());
    }
}
