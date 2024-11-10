<?php

namespace Src\Application\Services;
use Src\Domain\Loan\Loan;
use Src\Domain\Loan\LoanRepositoryInterface;
use Src\Domain\User\UserRepositoryInterface;
use Src\Domain\Book\BookRepositoryInterface;

class LoanService
{
    private LoanRepositoryInterface $loanRepository;
    private UserRepositoryInterface $userRepository;
    private BookRepositoryInterface $bookRepository;

    public function __construct(
        LoanRepositoryInterface $loanRepository,
        UserRepositoryInterface $userRepository,
        BookRepositoryInterface $bookRepository
    ) {
        $this->loanRepository = $loanRepository;
        $this->userRepository = $userRepository;
        $this->bookRepository = $bookRepository;
    }

    public function createLoan(int $userId, int $bookId, int $loanDurationDays): bool
    {
        if ($this->userRepository->findById($userId) === null) {
            throw new \InvalidArgumentException("User with ID $userId does not exist.");
        }

        if ($this->bookRepository->find($bookId) === null) {
            throw new \InvalidArgumentException("Book with ID $bookId does not exist.");
        }

        if ($this->hasExpiredPendingLoans($userId)) {
            throw new \DomainException("User with ID $userId has expired pending loans.");
        }

        $loanDate = new \DateTime();
        $expectedReturnDate = (new \DateTime())->modify("+$loanDurationDays days");

        $loan = new Loan($bookId, $userId, $loanDate, $expectedReturnDate, null, false, null);
        return $this->loanRepository->create($loan);
    }

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
