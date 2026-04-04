<?php

namespace Services;

use Models\User;
use Helper\JsonStorage;
use Services\UserService;
use Services\HistoryService;
use Services\GameService;
use Services\AuthService;
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
    private ?User $currentUser = null;
    public function __construct(
        private AuthService $authService,
        private UserService $user,
        private GameService $game,
        private HistoryService $history,
        private AbstractGame $typeGame
    ) {
        $this->userStorage = new JsonStorage('storage/user.json');
        $this->historyStorage = new JsonStorage('storage/history.json');
    }
    public function run(AuthService $authService): void
    {
        $this->currentUser = null;
        while (true) {
            if ($this->currentUser === null) {
                $this->toAccount();
                $action = readline("Action: ");
                try {
                    if ($action === '1') {
                        $name = readline("Enter login: ");
                        $password = readline("Enter password: ");

                        $currentUser = $authService->login($name, $password);
                        echo "Welcome to the system, " . $currentUser->getName() . "!" . PHP_EOL;
                    } elseif ($action === '2') {
                        $name = readline("Come up with a name: ");
                        $password = readline("Come up with a password: ");

                        $authService->registration($name, $password);
                        echo "You have successfully registered! Now log in." . PHP_EOL;
                    } else {
                        echo "Exit.." . PHP_EOL;
                        break;
                    }
                } catch (\Exception $e) {
                    echo "Error: " . $e->getMessage() . PHP_EOL;
                }continue;
            }
            $this->showMenu($this->currentUser);
            $choise = readline("Choose action: ");
            switch($choise){
             case '1':
                $this->whatToPlay();
                $typeGame = readline("Choose 1,2 or 3: ");
                $bet = readline((float)"Your bet: ");
                $this->game->validateBet($bet,$this->currentUser,$typeGame);
               $this->game->chooseGame($bet,$typeGame);


            }
        }
    }
    public function showMenu(User $user): void
    {
        echo "User: " . $user->getName() . " | Balance: " . $user->getBalance() . " grn" . PHP_EOL;
        echo "------------------------" . PHP_EOL;
        echo "1. Play" . PHP_EOL;
        echo "2. To Deposit" . PHP_EOL;
        echo "3. Game History " . PHP_EOL;
        echo "4. See balance" . PHP_EOL;
        echo "5. Statistics" . PHP_EOL;
        echo "6. Log out" . PHP_EOL;
        echo "7. Exit" . PHP_EOL;
    }
    public function toAccount(): void
    {
        echo "1. Sign in" . PHP_EOL;
        echo "2. Registration" . PHP_EOL;
    }
    public function whatToPlay(): void
    {
        echo "What do you want to play?" . PHP_EOL;
        echo "1. Dice" . PHP_EOL;
        echo "2. Coin flip" . PHP_EOL;
        echo "3. Slots" . PHP_EOL;
    }
}
