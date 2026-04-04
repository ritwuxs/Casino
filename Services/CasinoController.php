<?php

namespace Services;

use Models\User;
use Helper\JsonStorage;
use Services\UserService;
use Services\HistoryService;
use Games\AbstractGame;
use Games\CoinFlip;
use Games\Dice;
use Games\Slots;
use Exception\Exceptions\InsufficientBalanceException;
use Exception\Exceptions\InsufficientFundsException;
use Exception\Exceptions\NegativeAmountException;
use Exception\Exceptions\UserAlreadyExistsException;
use Exception\Exceptions\UserNotFoundException;
use Exception\Exceptions\BetException;


// TODO: вынести все из этого в CasinoController, приватные поля, основной метод, вспомогательные (в этом файле мы просто создаим объект контроллера, вызовем основной метод)
class CasinoController
{
    private JsonStorage $userStorage;
    private JsonStorage $historyStorage;
    private UserService $currentUser;
    public function __construct()
    {
        $this->userStorage = new JsonStorage('stoarge/user.json');
        $this->historyStorage=new JsonStorage('storage/history.json');
    }
}
