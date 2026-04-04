<?php

namespace Services;
use Models\User;
use Exceptions\UserAlreadyExistsException;
use Exceptions\UserNotFoundException;

use Helper\JsonStorage;


class AuthService{
    private JsonStorage $storage;
    public function __construct(JsonStorage $storage)
    {
        $this->storage = $storage;
    }

    public function registration(string $name, string $password): void // DO: добавить пароль, подтверждение пароля
    {
        $users = $this->storage->read();
        foreach ($users as $user) {
            if ($user['user_name'] === $name) {
                throw new UserAlreadyExistsException(); // DO: конкретное исключение
            }
        }
        $newId = count($users) > 0 ? max(array_column($users, 'user_id')) + 1 : 1;
        $users[] = [
            'user_id' => $newId,
            'user_name' => $name,
            'balance' => 0, // DO: уберем дефолтный баланс 1000, поставим 0
            'password' => $password  // DO: убрать историю из юзера, есть json файл history, с user_id, там понятно чья это история
        ];

        $this->storage->write($users);
    }
     public function login(string $name,string $password): User
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
        throw new UserNotFoundException(); // DO: UserNotFoundException
    }

}