<?php

namespace Tests\Application\Services;

use PHPUnit\Framework\TestCase;
use Src\Application\Services\LoanService;
use Src\Domain\Loan\Loan;
use Src\Domain\Loan\LoanRepositoryInterface;
use Src\Domain\User\UserRepositoryInterface;
use Src\Domain\User\User;

class LoanServiceTest extends TestCase
{
    private LoanService $loanService;
    private $loanRepositoryMock;
    private $userRepositoryMock;

    protected function setUp(): void
    {
        $this->loanRepositoryMock = $this->createMock(LoanRepositoryInterface::class);
        $this->userRepositoryMock = $this->createMock(UserRepositoryInterface::class);

        $this->loanService = new LoanService($this->loanRepositoryMock, $this->userRepositoryMock);
    }

    public function testCreateLoanSuccess(): void
    {
        $userId = 1;
        $bookId = 2;

        $this->userRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($userId)
            ->willReturn($this->createMock(User::class));

        $this->loanRepositoryMock->expects($this->once())
            ->method('findByUserId')
            ->with($userId)
            ->willReturn([]);

        $this->loanRepositoryMock->expects($this->once())
            ->method('create')
            ->willReturn(true);

        $result = $this->loanService->createLoan($userId, $bookId, 5);
        $this->assertTrue($result);
    }

    public function testCreateLoanFailsDueToExpiredPendingLoans(): void
    {
        $userId = 1;
        $bookId = 2;
        $loanDate = new \DateTime();

        $loan = $this->createMock(Loan::class);
        $loan->method('isReturned')->willReturn(false);
        $loan->method('getExpectedReturnDate')->willReturn(new \DateTime('-1 day'));

        $this->userRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($userId)
            ->willReturn($this->createMock(User::class));

        $this->loanRepositoryMock->expects($this->once())
            ->method('findByUserId')
            ->with($userId)
            ->willReturn([$loan]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("User with ID $userId has expired pending loans.");

        $this->loanService->createLoan($userId, $bookId, 5);
    }

    public function testMarkLoanAsReturned(): void
    {
        $loanId = 1;

        $loan = $this->createMock(Loan::class);

        $this->loanRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($loanId)
            ->willReturn($loan);

        $this->loanRepositoryMock->expects($this->once())
            ->method('update')
            ->with($loan)
            ->willReturn(true);

        $result = $this->loanService->markLoanAsReturned($loanId);

        $this->assertTrue($result);
    }


    public function testGetLoanById(): void
    {
        $loanId = 1;
        $loan = $this->createMock(Loan::class);

        $this->loanRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($loanId)
            ->willReturn($loan);

        $result = $this->loanService->getLoanById($loanId);
        $this->assertSame($loan, $result);
    }

    public function testGetLoansByUserId(): void
    {
        $userId = 1;
        $loans = [$this->createMock(Loan::class), $this->createMock(Loan::class)];

        $this->loanRepositoryMock->expects($this->once())
            ->method('findByUserId')
            ->with($userId)
            ->willReturn($loans);

        $result = $this->loanService->getLoansByUserId($userId);
        $this->assertSame($loans, $result);
    }

    public function testGetLoansByBookId(): void
    {
        $bookId = 1;
        $loans = [$this->createMock(Loan::class), $this->createMock(Loan::class)];

        $this->loanRepositoryMock->expects($this->once())
            ->method('findByBookId')
            ->with($bookId)
            ->willReturn($loans);

        $result = $this->loanService->getLoansByBookId($bookId);
        $this->assertSame($loans, $result);
    }

    public function testDeleteLoan(): void
    {
        $loanId = 1;

        $this->loanRepositoryMock->expects($this->once())
            ->method('delete')
            ->with($loanId)
            ->willReturn(true);

        $result = $this->loanService->deleteLoan($loanId);
        $this->assertTrue($result);
    }

    public function testGetAllLoans(): void
    {
        $loans = [$this->createMock(Loan::class), $this->createMock(Loan::class)];

        $this->loanRepositoryMock->expects($this->once())
            ->method('getAllLoans')
            ->willReturn($loans);

        $result = $this->loanService->getAllLoans();
        $this->assertSame($loans, $result);
    }
}
