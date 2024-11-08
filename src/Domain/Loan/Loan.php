<?php

namespace Src\Domain\Loan;

class Loan
{
    private $id;
    private $bookId;
    private $userId;
    private $loanDate;
    private $expectedReturnDate;
    private $returnDate;
    private $returned;
    
    public function __construct(
        $bookId,
        $userId,
        $loanDate,
        $expectedReturnDate,
        $returnDate = null,
        $returned = false,
        $id = null
    ) {
        $this->bookId = $bookId;
        $this->userId = $userId;
        $this->loanDate = $loanDate;
        $this->expectedReturnDate = $expectedReturnDate;
        $this->returnDate = $returnDate;
        $this->returned = $returned;
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getBookId()
    {
        return $this->bookId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getLoanDate()
    {
        return $this->loanDate;
    }

    public function getExpectedReturnDate()
    {
        return $this->expectedReturnDate;
    }

    public function setExpectedReturnDate($expectedReturnDate): void
    {
        $this->expectedReturnDate = $expectedReturnDate;
    }

    public function getReturnDate()
    {
        return $this->returnDate;
    }

    public function setReturnDate($returnDate): void
    {
        $this->returnDate = $returnDate;
    }

    public function isReturned(): bool
    {
        return $this->returned;
    }

    public function setReturned(bool $returned): void
    {
        $this->returned = $returned;
    }
}
