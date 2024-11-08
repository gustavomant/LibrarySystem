<?php

namespace Src\Infrastructure\Persistence;
use PDO;
use Src\Domain\Loan\Loan;
use Src\Domain\Loan\LoanRepositoryInterface;

class LoanRepository implements LoanRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(Loan $loan): bool
    {
        $sql = 'INSERT INTO loans (user_id, book_id, loan_date, expected_return_date, return_date, returned) 
                VALUES (:user_id, :book_id, :loan_date, :expected_return_date, :return_date, :returned)';
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'user_id' => $loan->getUserId(),
            'book_id' => $loan->getBookId(),
            'loan_date' => $loan->getLoanDate()->format('Y-m-d H:i:s'),
            'expected_return_date' => $loan->getExpectedReturnDate() ? $loan->getExpectedReturnDate()->format('Y-m-d H:i:s') : null,
            'return_date' => $loan->getReturnDate() ? $loan->getReturnDate()->format('Y-m-d H:i:s') : null,
            'returned' => $loan->isReturned(),
        ]);
    }

    public function findById($id): ?Loan
    {
        $sql = 'SELECT * FROM loans WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? $this->mapToLoan($data) : null;
    }

    public function findByUserId($userId): array
    {
        $sql = 'SELECT * FROM loans WHERE user_id = :user_id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([$this, 'mapToLoan'], $data);
    }

    public function findByBookId($bookId): array
    {
        $sql = 'SELECT * FROM loans WHERE book_id = :book_id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['book_id' => $bookId]);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([$this, 'mapToLoan'], $data);
    }

    public function update(Loan $loan): bool
    {
        $sql = 'UPDATE loans 
                SET user_id = :user_id, book_id = :book_id, loan_date = :loan_date, 
                    expected_return_date = :expected_return_date, return_date = :return_date, returned = :returned 
                WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id' => $loan->getId(),
            'user_id' => $loan->getUserId(),
            'book_id' => $loan->getBookId(),
            'loan_date' => $loan->getLoanDate()->format('Y-m-d H:i:s'),
            'expected_return_date' => $loan->getExpectedReturnDate() ? $loan->getExpectedReturnDate()->format('Y-m-d H:i:s') : null,
            'return_date' => $loan->getReturnDate() ? $loan->getReturnDate()->format('Y-m-d H:i:s') : null,
            'returned' => $loan->isReturned(),
        ]);
    }

    public function delete($id): bool
    {
        $sql = 'DELETE FROM loans WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }

    public function getAllLoans(): array
    {
        $sql = 'SELECT * FROM loans';
        $stmt = $this->pdo->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'mapToLoan'], $data);
    }

    private function mapToLoan(array $data): Loan
    {
        return new Loan(
            (int)$data['user_id'],
            (int)$data['book_id'],
            new \DateTime($data['loan_date']),
            $data['expected_return_date'] ? new \DateTime($data['expected_return_date']) : null,
            $data['return_date'] ? new \DateTime($data['return_date']) : null,
            (bool)$data['returned'],
            (int)$data['id'],
        );
    }
}
