<?php

use PHPUnit\Framework\TestCase;
use Src\Domain\Loan\Loan;
use Src\Domain\ValueObjects\ISBN;

class LoanTest extends TestCase
{
    public function testSettersAndGetters()
    {
        $loanDate = new \DateTime('2024-01-01');
        $expectedReturnDate = new \DateTime('2024-02-01');
        $returnDate = new \DateTime('2024-01-15');
        
        $loan = new Loan(1, 1, $loanDate, $expectedReturnDate, $returnDate);

        $loan->setReturnDate(new \DateTime('2024-01-20'));
        $loan->setReturned(true);

        $this->assertEquals(1, $loan->getBookId());
        $this->assertEquals(1, $loan->getUserId());
        $this->assertEquals($loanDate, $loan->getLoanDate());
        $this->assertEquals($expectedReturnDate, $loan->getExpectedReturnDate());
        $this->assertEquals(new \DateTime('2024-01-20'), $loan->getReturnDate());
        $this->assertTrue($loan->isReturned());
    }

    public function testConstructorWithNullableId()
    {
        $loanDate = new \DateTime('2024-01-01');
        $expectedReturnDate = new \DateTime('2024-02-01');
        
        $loan = new Loan(1, 1, $loanDate, $expectedReturnDate);

        $this->assertNull($loan->getId());
    }
}
