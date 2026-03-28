<?php

namespace Models;

use Exceptions\UserException;

class User
{
    public function __construct(
        private int $userId, // TODO: тут и так понятно что это id юзера, можно просто id 
        private string $userName, // TODO: тоже самое
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
    public function deposit(float $amount): float // TODO: выносим в сервис (логика это задача сервисов)
    {
        if ($amount <= 0) {
            throw new UserException("Amount could not be negative"); // TODO: сделать конкретное исключение NegativeAmountException
        }
        return  $this->balance += $amount;
    }
    public function withdraw(float $amount): float // TODO: выносим в сервис
    {
        if ($amount <= 0) {
            throw new UserException("Amount could not be negative"); // TODO: сделать конкретное исключение NegativeAmountException
        }
        if ($amount > $this->balance) {
            throw new UserException("Not enough money"); // TODO: сделать конкретное исключение 
        }
        return  $this->balance -= $amount;
    }
}
