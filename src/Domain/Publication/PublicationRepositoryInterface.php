<?php

namespace Src\Domain\Publication;

interface PublicationRepositoryInterface
{
    public function create(Publication $publication): bool;
    public function getAll(): array;
    public function find(int $id): ?Publication;
    public function update(int $id, Publication $publication): bool;
    public function delete(int $id): bool;
}
