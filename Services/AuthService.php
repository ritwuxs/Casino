<?php

namespace Services;

use Models\User;
use Exceptions\UserAlreadyExistsException;
use Exceptions\UserNotFoundException;

use Helper\JsonStorage;


class AuthService
{
    private JsonStorage $storage;
    public function __construct(JsonStorage $storage)
    {
        $this->storage = $storage; // TODO: здесь можем сразу инициализировать storage , $this->storage = new JsonStorage(...);
    }

    public function registration(string $name, string $password): void 
    {
        $users = $this->storage->read();

        foreach ($users as $user) {
            if ($user['name'] === $name) {
                throw new UserAlreadyExistsException();
            }
        }
        $confirmPassword = readline("Confirm your password: ");
        if ($password == $confirmPassword) {
            $newId = count($users) > 0 ? max(array_column($users, 'id')) + 1 : 1;
            $users[] = [
                'id' => $newId,
                'name' => $name,
                'balance' => 0,
                'password' => $password
            ];
            $this->storage->write($users);
        }
    }

    public function login(string $name, string $password): User
    {
        $users = $this->storage->read();
        foreach ($users as $user) {
            if ($user['name'] === $name && $user['password'] === $password) {
                return new User(
                    $user['id'],
                    $user['name'],
                    (float) $user['balance'],
                    $user['password']
                );
            }
        }
        throw new UserNotFoundException();
    }
}
