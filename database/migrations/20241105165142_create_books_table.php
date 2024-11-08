<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateBooksTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('books');

        $table
            ->addColumn('publication_id', 'integer')
            ->addForeignKey('publication_id', 'publications', 'id', [
                'delete' => 'RESTRICT',
                'update' => 'NO_ACTION'
            ])
            ->create();
    }
}
