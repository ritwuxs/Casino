<?php

namespace Models;

use Exceptions\UserException;

class User
{
    public function __construct(
        private int $userId,
        private string $userName,
        private float $balance
    ) {}
    public function getUserId(): int
    {
        return $this->userId;
    }
    public function getName(): string
    {
        return $this->userName;
    }
    public function getBalance(): float
    {
        return $this->balance;
    }
    public function deposit(float $amount): float
    {
        if ($amount <= 0) {
            throw new UserException("Amount could not be negative");
        }
        return  $this->balance += $amount;
    }
    public function withdraw(float $amount): float
    {
        if ($amount <= 0) {
            throw new UserException("Amount could not be negative");
        }
        if ($amount > $this->balance) {
            throw new UserException("Not enough money");
        }
        return  $this->balance -= $amount;
    }
}
