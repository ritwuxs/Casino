<?php

namespace Services;

use Models\User;
use Exceptions\UserException;
use Helper\JsonStorage;

class UserService
{
    private JsonStorage $storage;
    public function __construct(JsonStorage $storage)
    {
        $this->storage = $storage;
    }

    // TODO: вынести в AuthService
    public function registration(string $userName): void // TODO: добавить пароль, подтверждение пароля
    {
        $users = $this->storage->read();
        foreach ($users as $user) {
            if ($user['username'] === $userName) {
                throw new UserException("User arleady exists.UserName has to be uniq"); // TODO: конкретное исключение
            }
        }
        $newId = count($users) > 0 ? max(array_column($users, 'id')) + 1 : 1;
        $users[] = [
            'id' => $newId,
            'username' => $userName,
            'balance' => 1000.0, // TODO: уберем дефолтный баланс 1000, поставим 0
            'history' => [] // TODO: убрать историю из юзера, есть json файл history, с user_id, там понятно чья это история
        ];

        $this->storage->write($users);
    }

    // TODO: вынести в AuthService
    public function login(string $userName): User
    {
        $users = $this->storage->read();
        foreach ($users as $user) {
            if ($user['username'] === $userName) {
                return new User(
                    $user['id'],
                    $user['username'],
                    $user['balance']
                );
            }
        }
        throw new UserException("User not found"); // TODO: UserNotFoundException
    }
    public function updateUser(User $user): void
    {
        $users = $this->storage->read();
        foreach ($users as &$userData) {
            if ($userData['id'] === $user->getUserId()) {
                $userData = [
                    'id'       => $user->getUserId(),
                    'username' => $user->getName(),
                    'balance'  => $user->getBalance(),
                    'history'  => $userData['history'] ?? []
                ];
                break;
            }
        }
        unset($userData);
        $this->storage->write($users);
    }
}
