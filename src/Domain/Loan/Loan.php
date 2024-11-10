<?php

namespace Src\Domain\Loan;

class Loan implements \JsonSerializable
{
    private ?int $id;
    private int $bookId;
    private int $userId;
    private \DateTime $loanDate;
    private ?\DateTime $expectedReturnDate;
    private ?\DateTime $returnDate;
    private bool $returned;
    
    public function __construct(
        int $bookId,
        int $userId,
        \DateTime $loanDate,
        ?\DateTime $expectedReturnDate = null,
        ?\DateTime $returnDate = null,
        bool $returned = false,
        ?int $id = null
    ) {
        $this->bookId = $bookId;
        $this->userId = $userId;
        $this->loanDate = $loanDate;
        $this->expectedReturnDate = $expectedReturnDate;
        $this->returnDate = $returnDate;
        $this->returned = $returned;
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookId(): int
    {
        return $this->bookId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getLoanDate(): \DateTime
    {
        return $this->loanDate;
    }

    public function getExpectedReturnDate(): ?\DateTime
    {
        return $this->expectedReturnDate;
    }

    public function getReturnDate(): ?\DateTime
    {
        return $this->returnDate;
    }

    public function isReturned(): bool
    {
        return $this->returned;
    }

    public function setExpectedReturnDate(?\DateTime $expectedReturnDate): void
    {
        $this->expectedReturnDate = $expectedReturnDate;
    }

    public function setReturnDate(?\DateTime $returnDate): void
    {
        $this->returnDate = $returnDate;
    }

    public function setReturned(bool $returned): void
    {
        $this->returned = $returned;
    }

    public function jsonSerialize() {
        return [
            "id" => $this->getId(),
            "book_id" => $this->getBookId(),
            "user_id" => $this->getUserId(),
            "loan_date" => $this->getLoanDate(),
            "expected_return_date" => $this->getExpectedReturnDate(),
            "return_date" => $this->getReturnDate(),
            "returned" => $this->isReturned()
        ];
    }
}
