<?php

namespace Src\Application\Services;
use Src\Domain\Loan\Loan;
use Src\Domain\Loan\LoanRepositoryInterface;
use Src\Domain\User\UserRepositoryInterface;

class LoanService
{
    private LoanRepositoryInterface $loanRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(LoanRepositoryInterface $loanRepository, UserRepositoryInterface $userRepository)
    {
        $this->loanRepository = $loanRepository;
        $this->userRepository = $userRepository;
    }

    public function createLoan(int $userId, int $bookId, int $loanDurationDays): bool
    {
        if ($this->userRepository->findById($userId) === null) {
            throw new \InvalidArgumentException("User with ID $userId does not exist.");
        }

        if ($this->hasExpiredPendingLoans($userId)) {
            throw new \DomainException("User with ID $userId has expired pending loans.");
        }

        $loanDate = new \DateTime();
        $expectedReturnDate = (new \DateTime())->modify("+$loanDurationDays days");

        $loan = new Loan($bookId, $userId, $loanDate, $expectedReturnDate, null, false, null);
        return $this->loanRepository->create($loan);
    }

    /**
     * Marks a loan as returned by updating only return_date and returned status.
     * 
     * @param int $loanId The ID of the loan to be updated.
     * @param bool $returned The status to mark as returned.
     * @return bool True if the update was successful, otherwise false.
     */
    public function markLoanAsReturned(int $loanId): bool
    {
        $loan = $this->loanRepository->findById($loanId);

        if (!$loan) {
            return false;
        }

        $loan->setReturnDate(new \DateTime());
        $loan->setReturned(true);

        return $this->loanRepository->update($loan);
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

    public function deleteLoan(int $id): bool
    {
        return $this->loanRepository->delete($id);
    }

    public function getAllLoans(): array
    {
        return $this->loanRepository->getAllLoans();
    }
}
