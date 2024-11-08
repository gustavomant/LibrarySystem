<?php

use PHPUnit\Framework\TestCase;
use Src\Infrastructure\Persistence\LoanRepository;
use Src\Domain\Loan\Loan;
use PDO;

class LoanRepositoryTest extends TestCase
{
    private $pdoMock;
    private $loanRepository;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->loanRepository = new LoanRepository($this->pdoMock);
    }

    public function testCreate()
    {
        $loan = new Loan(1, 2, new \DateTime('2024-01-01'), new \DateTime('2024-02-01'), null, false);
        $stmtMock = $this->createMock(PDOStatement::class);
        
        $stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtMock);
        
        $result = $this->loanRepository->create($loan);

        $this->assertTrue($result);
    }

    public function testFindById()
    {
        $loanData = [
            'id' => 1,
            'user_id' => 1,
            'book_id' => 1,
            'loan_date' => '2024-01-01 10:00:00',
            'expected_return_date' => '2024-02-01 10:00:00',
            'return_date' => null,
            'returned' => false
        ];

        $stmtMock = $this->createMock(PDOStatement::class);
        
        $stmtMock->expects($this->once())
            ->method('fetch')
            ->willReturn($loanData);

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtMock);

        $loan = $this->loanRepository->findById(1);

        $this->assertInstanceOf(Loan::class, $loan);
        $this->assertEquals(1, $loan->getId());
        $this->assertEquals(1, $loan->getUserId());
        $this->assertEquals(1, $loan->getBookId());
    }

    public function testFindByUserId()
    {
        $loanData = [
            'id' => 1,
            'user_id' => 1,
            'book_id' => 1,
            'loan_date' => '2024-01-01 10:00:00',
            'expected_return_date' => '2024-02-01 10:00:00',
            'return_date' => null,
            'returned' => false
        ];

        $stmtMock = $this->createMock(PDOStatement::class);
        
        $stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn([$loanData]);

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtMock);

        $loans = $this->loanRepository->findByUserId(1);

        $this->assertCount(1, $loans);
        $this->assertInstanceOf(Loan::class, $loans[0]);
    }

    public function testUpdate()
    {
        $loan = new Loan(1, 2, new \DateTime('2024-01-01'), new \DateTime('2024-02-01'), null, false);
        $stmtMock = $this->createMock(PDOStatement::class);
        
        $stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtMock);
        
        $result = $this->loanRepository->update($loan);

        $this->assertTrue($result);
    }

    public function testDelete()
    {
        $stmtMock = $this->createMock(PDOStatement::class);
        
        $stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtMock);

        $result = $this->loanRepository->delete(1);

        $this->assertTrue($result);
    }

    public function testGetAllLoans()
    {
        $loanData = [
            'id' => 1,
            'user_id' => 1,
            'book_id' => 1,
            'loan_date' => '2024-01-01 10:00:00',
            'expected_return_date' => '2024-02-01 10:00:00',
            'return_date' => null,
            'returned' => false
        ];

        $stmtMock = $this->createMock(PDOStatement::class);
        
        $stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn([$loanData]);

        $this->pdoMock->expects($this->once())
            ->method('query')
            ->willReturn($stmtMock);

        $loans = $this->loanRepository->getAllLoans();

        $this->assertCount(1, $loans);
        $this->assertInstanceOf(Loan::class, $loans[0]);
    }
}
