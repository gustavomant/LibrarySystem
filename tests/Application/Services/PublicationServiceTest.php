<?php

namespace Tests\Application\Services;

use PHPUnit\Framework\TestCase;
use Src\Application\Services\PublicationService;
use Src\Domain\Publication\Publication;
use Src\Domain\Publication\PublicationRepositoryInterface;
use Src\Domain\ValueObjects\ISBN;

class PublicationServiceTest extends TestCase
{
    private PublicationService $publicationService;
    private $publicationRepositoryMock;

    protected function setUp(): void
    {
        $this->publicationRepositoryMock = $this->createMock(PublicationRepositoryInterface::class);
        $this->publicationService = new PublicationService($this->publicationRepositoryMock);
    }

    public function testCreatePublicationSuccess(): void
    {
        $isbn = new ISBN('9783161484100');
        $title = 'Book Title';
        $author = 'Book Author';
        $publishedYear = 2020;

        $publication = new Publication($title, $author, $publishedYear, $isbn);

        $this->publicationRepositoryMock->expects($this->once())
            ->method('create')
            ->with($publication)
            ->willReturn(true);

        $result = $this->publicationService->createPublication($title, $author, $publishedYear, $isbn);
        $this->assertTrue($result);
    }

    public function testGetAllPublications(): void
    {
        $publications = [
            new Publication('Book Title 1', 'Author 1', 2020, new ISBN('9783161484101')),
            new Publication('Book Title 2', 'Author 2', 2021, new ISBN('9783161484102'))
        ];

        $this->publicationRepositoryMock->expects($this->once())
            ->method('getAll')
            ->willReturn($publications);

        $result = $this->publicationService->getAllPublications();
        $this->assertSame($publications, $result);
    }

    public function testFindPublication(): void
    {
        $publicationId = 1;
        $publication = new Publication('Book Title', 'Book Author', 2020, new ISBN('9783161484100'), $publicationId);

        $this->publicationRepositoryMock->expects($this->once())
            ->method('find')
            ->with($publicationId)
            ->willReturn($publication);

        $result = $this->publicationService->findPublication($publicationId);
        $this->assertSame($publication, $result);
    }

    public function testUpdatePublication(): void
    {
        $publicationId = 1;
        $isbn = new ISBN('9783161484101');
        $title = 'Updated Book Title';
        $author = 'Updated Book Author';
        $publishedYear = 2022;

        $publication = new Publication($title, $author, $publishedYear, $isbn, $publicationId);

        $this->publicationRepositoryMock->expects($this->once())
            ->method('update')
            ->with($publicationId, $publication)
            ->willReturn(true);

        $result = $this->publicationService->updatePublication($publicationId, $isbn, $title, $author, $publishedYear);
        $this->assertTrue($result);
    }

    public function testDeletePublication(): void
    {
        $publicationId = 1;

        $this->publicationRepositoryMock->expects($this->once())
            ->method('delete')
            ->with($publicationId)
            ->willReturn(true);

        $result = $this->publicationService->deletePublication($publicationId);
        $this->assertTrue($result);
    }
}
