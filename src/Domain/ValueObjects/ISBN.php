<?php
namespace Src\Domain\ValueObjects;

class ISBN
{
    private string $value;

    public function __construct(string $value)
    {
        if (!$this->isValid($value)) {
            throw new \InvalidArgumentException("Invalid ISBN format.");
        }
        $this->value = $value;
    }

    private function isValid(string $value): bool
    {
        return preg_match('/^(?:ISBN(?:-1[03])?:? )?(?=[0-9]{9}X?$|[0-9]{13}$)([0-9]+[- ]?){3,5}[0-9X]$/', $value) === 1;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
