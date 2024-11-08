<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateLoansTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('loans');
        $table
            ->addColumn('user_id', 'integer')
            ->addColumn('book_id', 'integer')
            ->addColumn('loan_date', 'datetime')
            ->addColumn('expected_return_date', 'datetime')
            ->addColumn('return_date', 'datetime', ['null' => true])
            ->addColumn('returned', 'boolean', ['default' => false])
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->addForeignKey('book_id', 'books', 'id', ['delete'=> 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->create();
    }
}
