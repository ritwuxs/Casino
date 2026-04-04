<?php

namespace Services;

use Models\User;
use Exceptions\UserAlreadyExistsException;
use Exceptions\UserNotFoundException;
use Exceptions\NegativeAmountException;
use Exceptions\InsufficientBalanceException;


use Helper\JsonStorage;

class UserService
{
    private JsonStorage $storage;
    public function __construct(JsonStorage $storage)
    {
        $this->storage = $storage;
    }

    
    public function updateUser(User $user): void
    {
        $users = $this->storage->read();
        foreach ($users as &$userData) {
            if ($userData['user_id'] === $user->getUserId()) {
                $userData = [
                    'user_id'       => $user->getUserId(),
                    'user_name' => $user->getName(),
                    'balance'  => $user->getBalance()
                ];
                break;
            }
        }
        unset($userData);
        $this->storage->write($users);
    }
    public function deposit(User $user, float $amount): float // DO: выносим в сервис (логика это задача сервисов)
    {
        if ($amount <= 0) {
            throw new NegativeAmountException(); // DO: сделать конкретное исключение NegativeAmountException
        }
        $newBalance = $user->getBalance() + $amount;
        $user->setBalance($newBalance);
        $this->updateUser($user);

        return $newBalance;
    }
    public function withdraw(User $user, float $amount): float // DO: выносим в сервис
    {
        if ($amount <= 0) {
            throw new NegativeAmountException(); // DO: сделать конкретное исключение NegativeAmountException
        }
        if ($amount > $user->getBalance()) {
            throw new InsufficientBalanceException(); // DO: сделать конкретное исключение 
        }
        $newBalance = $user->getBalance() - $amount;
        $user->setBalance($newBalance);
        $this->updateUser($user);
        return $newBalance;
    }
    public function makeBet(): float
    {
        $input = readline("Enter your bet: ");
        return $bet = (float)$input;
    }
}
