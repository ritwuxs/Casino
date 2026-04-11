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
            if ($userData['id'] === $user->getId()) {
                $userData = [
                    'id' => $user->getId(), //DO:Пробел
                    'name' => $user->getName(),
                    'balance' => $user->getBalance(),
                    'password' => $user->getPassword()

                ];
                break;
            }
        }
        unset($userData);
        $this->storage->write($users);
    }

    public function deposit(User $user, float $amount): float
    {
        if ($amount <= 0) {
            throw new NegativeAmountException();
        }
        $newBalance = $user->getBalance() + $amount;
        $user->setBalance($newBalance);
        $this->updateUser($user);

        return $newBalance;
    }

    public function withdraw(User $user, float $amount): float
    {
        if ($amount <= 0) {
            throw new NegativeAmountException();
        }
        if ($amount > $user->getBalance()) {
            throw new InsufficientBalanceException();
        }
        $newBalance = $user->getBalance() - $amount;
        $user->setBalance($newBalance);
        $this->updateUser($user);
        return $newBalance;
    }
    public function makeBet(): float
    {
        $input = readline("Enter your bet: ");
        return (float)$input; // DO: return (float) $input
    }
}
