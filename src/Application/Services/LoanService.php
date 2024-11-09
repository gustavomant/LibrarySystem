<?php

namespace Src\Application\Services;

use Src\Domain\Loan\Loan;
use Src\Domain\Loan\LoanRepositoryInterface;

class LoanService
{
    private LoanRepositoryInterface $loanRepository;

    public function __construct(LoanRepositoryInterface $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }

    public function createLoan(int $userId, int $bookId, \DateTime $loanDate, ?\DateTime $expectedReturnDate = null, ?\DateTime $returnDate = null): bool
    {
        if ($this->hasExpiredPendingLoans($userId)) {
            return false;
        }

        $loan = new Loan($bookId, $userId, $loanDate, $expectedReturnDate, $returnDate, false);
        return $this->loanRepository->create($loan);
    }

    /**
     * Determines if the specified user has any pending loans that are past their expected return date.
     *
     * This function retrieves all loans associated with the given user ID and checks each loan to see if it 
     * has not been returned and if its expected return date has already passed.
     *
     * @param int $userId The ID of the user whose loans are being checked.
     * @return bool True if there is at least one pending loan past its due date; otherwise, false.
     */
    private function hasExpiredPendingLoans(int $userId): bool
    {
        $loans = $this->loanRepository->findByUserId($userId);

        foreach ($loans as $loan) {
            if (!$loan->isReturned() && $loan->getExpectedReturnDate() < new \DateTime()) {
                return true;
            }
        }

        return false;
    }

    public function getLoanById(int $id): ?Loan
    {
        return $this->loanRepository->findById($id);
    }

    public function getLoansByUserId(int $userId): array
    {
        return $this->loanRepository->findByUserId($userId);
    }

    public function getLoansByBookId(int $bookId): array
    {
        return $this->loanRepository->findByBookId($bookId);
    }

    public function updateLoan(int $userId, int $bookId, \DateTime $loanDate, \DateTime $expectedReturnDate, ?\DateTime $returnDate, bool $returned, int $id): bool
    {
        $loan = new Loan($userId, $bookId, $loanDate, $expectedReturnDate, $returnDate, $returned, $id);
        return $this->loanRepository->update($loan);
    }

    public function deleteLoan(int $id): bool
    {
        return $this->loanRepository->delete($id);
    }

    public function getAllLoans(): array
    {
        return $this->loanRepository->getAllLoans();
    }
}
