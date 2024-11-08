<?php

namespace Src\Infrastructure\Persistence;

use PDO;
use Src\Domain\Publication\Publication;
use Src\Domain\Publication\PublicationRepositoryInterface;
use Src\Domain\ValueObjects\ISBN;

class PublicationRepository implements PublicationRepositoryInterface
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(Publication $publication): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO publications (isbn, title, author, published_year) 
            VALUES (:isbn, :title, :author, :published_year)
        ");
        
        $stmt->bindValue(':isbn', $publication->getIsbn()->getValue());
        $stmt->bindValue(':title', $publication->getTitle());
        $stmt->bindValue(':author', $publication->getAuthor());
        $stmt->bindValue(':published_year', $publication->getPublishedYear());
        
        return $stmt->execute();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM publications");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $publications = [];
        foreach ($rows as $row) {
            $publications[] = $this->mapRowToPublication($row);
        }
        
        return $publications;
    }

    public function find(int $id): ?Publication
    {
        $stmt = $this->db->prepare("SELECT * FROM publications WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row ? $this->mapRowToPublication($row) : null;
    }

    public function update(int $id, Publication $publication): bool
    {
        $stmt = $this->db->prepare("
            UPDATE publications 
            SET isbn = :isbn, title = :title, author = :author, published_year = :published_year 
            WHERE id = :id
        ");
        
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':isbn', $publication->getIsbn()->getValue());
        $stmt->bindValue(':title', $publication->getTitle());
        $stmt->bindValue(':author', $publication->getAuthor());
        $stmt->bindValue(':published_year', $publication->getPublishedYear());
        
        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM publications WHERE id = :id");
        $stmt->bindValue(':id', $id);
        
        return $stmt->execute();
    }

    private function mapRowToPublication(array $row): Publication
    {
        $isbn = new ISBN($row['isbn']);
        
        return new Publication(
            $row['title'],
            $row['author'],
            (int)$row['published_year'],
            $isbn,
            (int)$row['id']
        );
    }

}
