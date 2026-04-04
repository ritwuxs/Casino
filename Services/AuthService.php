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
        $this->storage = $storage;
    }

    public function registration(string $name, string $password): void // TODO: подтверждение пароля
    {
        $users = $this->storage->read();
        foreach ($users as $user) {
            if ($user['user_name'] === $name && $user['password'] === $password) { // TODO: достаточно проверить по username
                throw new UserAlreadyExistsException();
            }
        }
        $newId = count($users) > 0 ? max(array_column($users, 'user_id')) + 1 : 1;
        $users[] = [
            'user_id' => $newId,
            'user_name' => $name,
            'balance' => 0,
            'password' => $password
        ];

        $this->storage->write($users);
    }
    public function login(string $name, string $password): User
    {
        $users = $this->storage->read();
        foreach ($users as $user) {
            if ($user['user_name'] === $name && $user['password'] === $password) {
                return new User(
                    $user['user_id'],
                    $user['user_name'],
                    (float) $user['balance'],
                    $user['password']
                );
            }
        }
        throw new UserNotFoundException();
    }
}
