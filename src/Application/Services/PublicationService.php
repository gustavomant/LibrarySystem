<?php

namespace Src\Application\Services;

use Src\Domain\Publication\Publication;
use Src\Domain\Publication\PublicationRepositoryInterface;
use Src\Domain\ValueObjects\ISBN;

class PublicationService
{
    private PublicationRepositoryInterface $publicationRepository;

    public function __construct(PublicationRepositoryInterface $publicationRepository)
    {
        $this->publicationRepository = $publicationRepository;
    }

    public function createPublication(ISBN $isbn, string $title, string $author, int $publishedYear): bool
    {
        $publication = new Publication(0, $title, $author, $publishedYear, $isbn);
                
        return $this->publicationRepository->create($publication);
    }

    public function getAllPublications(): array
    {
        return $this->publicationRepository->getAll();
    }

    public function findPublication(int $id): ?Publication
    {
        return $this->publicationRepository->find($id);
    }

    public function updatePublication(int $id, ISBN $isbn, string $title, string $author, int $publishedYear): bool
    {
        $publication = new Publication($id, $title, $author, $publishedYear, $isbn);
                
        return $this->publicationRepository->update($id, $publication);
    }

    public function deletePublication(int $id): bool
    {
        return $this->publicationRepository->delete($id);
    }
}
