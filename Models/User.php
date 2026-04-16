<?php

namespace Models;

use Exceptions\InsufficientBalanceException;

class User
{
    public function __construct(
        private int $id,
        private string $name,
        private float $balance,
        private string $password
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setBalance(float $balance): void
    {
        if ($balance < 0) {
            throw new InsufficientBalanceException();
        }
        $this->balance = $balance;
    }
}
