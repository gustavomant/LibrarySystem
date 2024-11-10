<?php

namespace Tests\Application\Services;
use PHPUnit\Framework\TestCase;
use Src\Application\Services\LoanService;
use Src\Domain\Loan\Loan;
use Src\Domain\Loan\LoanRepositoryInterface;
use Src\Domain\User\UserRepositoryInterface;
use Src\Domain\User\User;
use Src\Domain\Book\Book;
use Src\Domain\Book\BookRepositoryInterface;

class LoanServiceTest extends TestCase
{
    private LoanService $loanService;
    private $loanRepositoryMock;
    private $userRepositoryMock;
    private $bookRepositoryMock;

    protected function setUp(): void
    {
        $this->loanRepositoryMock = $this->createMock(LoanRepositoryInterface::class);
        $this->userRepositoryMock = $this->createMock(UserRepositoryInterface::class);
        $this->bookRepositoryMock = $this->createMock(BookRepositoryInterface::class);

        $this->loanService = new LoanService(
            $this->loanRepositoryMock,
            $this->userRepositoryMock,
            $this->bookRepositoryMock
        );
    }

    public function testCreateLoanSuccess(): void
    {
        $userId = 1;
        $bookId = 2;

        $userMock = $this->createMock(User::class);
        $bookMock = $this->createMock(Book::class);

        $this->userRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($userId)
            ->willReturn($userMock);

        $this->bookRepositoryMock->expects($this->once())
            ->method('find')
            ->with($bookId)
            ->willReturn($bookMock);

        $this->loanRepositoryMock->expects($this->once())
            ->method('create')
            ->willReturn(true);

        $result = $this->loanService->createLoan($userId, $bookId, 5);

        $this->assertTrue($result);
    }

    public function testCreateLoanFailsDueToNonExistentUser(): void
    {
        $userId = 1;
        $bookId = 2;

        $this->userRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($userId)
            ->willReturn(null);

        $this->expectException(\InvalidArgumentException::class);

        $this->loanService->createLoan($userId, $bookId, 5);
    }

    public function testCreateLoanFailsDueToNonExistentBook(): void
    {
        $userId = 1;
        $bookId = 2;

        $userMock = $this->createMock(User::class);

        $this->userRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($userId)
            ->willReturn($userMock);

        $this->bookRepositoryMock->expects($this->once())
            ->method('find')
            ->with($bookId)
            ->willReturn(null);

        $this->expectException(\InvalidArgumentException::class);

        $this->loanService->createLoan($userId, $bookId, 5);
    }


    public function testCreateLoanFailsDueToExpiredPendingLoans(): void
    {
        $userId = 1;
        $bookId = 2;

        $loan = $this->createMock(Loan::class);
        $loan->method('isReturned')->willReturn(false);
        $loan->method('getExpectedReturnDate')->willReturn(new \DateTime('-1 day'));

        $userMock = $this->createMock(User::class);
        $bookMock = $this->createMock(Book::class);

        $this->userRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($userId)
            ->willReturn($userMock);

        $this->bookRepositoryMock->expects($this->once())
            ->method('find')
            ->with($bookId)
            ->willReturn($bookMock);

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
