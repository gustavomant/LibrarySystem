<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreatePublicationsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('publications');

        $table
            ->addColumn('title', 'string', ['limit' => 255])
            ->addColumn('author', 'string', ['limit' => 100])
            ->addColumn('published_year', 'integer', ['limit' => 4])
            ->addColumn('isbn', 'string', ['limit' => 13])
            ->addIndex(['isbn'], ['unique' => true])
            ->create();
    }
}
