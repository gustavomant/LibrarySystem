<?php

use PHPUnit\Framework\TestCase;
use Src\Domain\ValueObjects\ISBN;

class ISBNTest extends TestCase
{
    public function testCanCreateValidISBN()
    {
        $isbn = new ISBN('9783161484100');
        $this->assertEquals('9783161484100', $isbn->getValue());
    }

    public function testInvalidISBNThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        new ISBN('invalid-isbn');
    }
}
