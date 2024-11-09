<?php

namespace Tests\Application\Services;

use PHPUnit\Framework\TestCase;
use Src\Application\Services\LoanService;
use Src\Domain\Loan\Loan;
use Src\Domain\Loan\LoanRepositoryInterface;

class LoanServiceTest extends TestCase
{
    private LoanService $loanService;
    private $loanRepositoryMock;

    protected function setUp(): void
    {
        $this->loanRepositoryMock = $this->createMock(LoanRepositoryInterface::class);
        $this->loanService = new LoanService($this->loanRepositoryMock);
    }

    public function testCreateLoanSuccess(): void
    {
        $userId = 1;
        $bookId = 2;
        $loanDate = new \DateTime();
        $expectedReturnDate = new \DateTime('+7 days');

        $this->loanRepositoryMock->expects($this->once())
            ->method('findByUserId')
            ->with($userId)
            ->willReturn([]);

        $this->loanRepositoryMock->expects($this->once())
            ->method('create')
            ->willReturn(true);

        $result = $this->loanService->createLoan($userId, $bookId, $loanDate, $expectedReturnDate);
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

        $this->loanRepositoryMock->expects($this->once())
            ->method('findByUserId')
            ->with($userId)
            ->willReturn([$loan]);

        $result = $this->loanService->createLoan($userId, $bookId, $loanDate);
        $this->assertFalse($result);
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

    public function testUpdateLoan(): void
    {
        $loanId = 1;
        $userId = 1;
        $bookId = 2;
        $loanDate = new \DateTime();
        $expectedReturnDate = new \DateTime('+7 days');
        $returnDate = new \DateTime();
        $returned = true;

        $loan = new Loan($userId, $bookId, $loanDate, $expectedReturnDate, $returnDate, $returned, $loanId);

        $this->loanRepositoryMock->expects($this->once())
            ->method('update')
            ->with($this->equalTo($loan))
            ->willReturn(true);

        $result = $this->loanService->updateLoan($userId, $bookId, $loanDate, $expectedReturnDate, $returnDate, $returned, $loanId);
        $this->assertTrue($result);
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
