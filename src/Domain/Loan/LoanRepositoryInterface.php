<?php
namespace Src\Domain\Loan;

interface LoanRepositoryInterface
{
    public function create(Loan $loan): bool;
    public function findById($id): ?Loan;
    public function findByUserId($userId): array;
    public function findByBookId($bookId): array;
    public function update(Loan $loan): bool;
    public function delete($id): bool;
    public function getAllLoans(): array;
}
