<?php

namespace Controllers;

use enums\GameType;
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
use Exception\Exceptions\WrongType;
use Exceptions\WrongType as ExceptionsWrongType;

class CasinoController
{
    private JsonStorage $userStorage; // TODO: удаляем ненужные свойства
    private JsonStorage $historyStorage; // TODO: удаляем ненужные свойства
    private ?User $currentUser = null;
    public function __construct(
        private AuthService $authService, // TODO: сервисы инициализируем в контроллере
        private GameService $game,
        private HistoryService $history,
        private UserService $userService,
        private \Helper\ReadConfig $config // TODO: удаляем ненужные свойства
    ) {
        $this->userStorage = new JsonStorage('storage/user.json');
        $this->historyStorage = new JsonStorage('storage/history.json');
    }
    public function run(): void // TODO: обрабатываем все исключения
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

                        $this->currentUser = $this->authService->login($name, $password);
                        echo "Welcome to the system, " . $this->currentUser->getName() . "!" . PHP_EOL;
                    } elseif ($action === '2') {
                        $name = readline("Come up with a name: ");
                        $password = readline("Come up with a password: ");

                        $this->authService->registration($name, $password);
                        echo "You have successfully registered! Now log in." . PHP_EOL;
                    } else {
                        echo "Exit.." . PHP_EOL;
                        break;
                    }
                } catch (\Exception $e) {
                    echo "Error: " . $e->getMessage() . PHP_EOL;
                }
                continue;
            }
            $this->showMenu($this->currentUser);
            $choise = readline("Choose action: ");
            switch ($choise) {
                case '1':
                    $this->whatToPlay();
                    $typeGame = readline("Choose 1,2,3 or 4: ");
                    $selectedEnum = match ($typeGame) {
                        '1' => \Enums\gameType::DICE,
                        '2' => \Enums\gameType::COIN_FLIP,
                        '3' => \Enums\gameType::SLOTS,
                        '4' => \Enums\gameType::BLACK_JACK,
                        default => null
                    };
                    if ($selectedEnum === null) {
                        throw new ExceptionsWrongType();
                    }
                    $bet = readline("Your bet: ");
                    try {
                        $result = $this->game->runGame($this->currentUser, $selectedEnum, (float)$bet);
                        echo $result['message'] . PHP_EOL;
                        break;
                    } catch (\Exception $e) {
                        echo "Error" . $e->getMessage() . PHP_EOL;
                    }
                    readline("Press Enter to continue...");
                    break;
                case '2':
                    $this->handleDeposit();
                    break;

                case '3':
                    $this->history->showUserHistory($this->currentUser->getId());
                    break;

                case '4':
                    echo " Your current balance: " . $this->currentUser->getBalance() . " grn" . PHP_EOL;
                    break;

                case '5':
                    $this->history->showUserStatistics($this->currentUser);
                    break;

                case '6':
                    $this->handleLogout();
                    break;

                case '7':
                    echo "Exiting the casino. Good luck!" . PHP_EOL;
                    exit;
            }
        }
    }
    public function handleDeposit(): void
    {
        echo "Enter amount: ";
        $amount = readLine("");
        $this->userService->deposit($this->currentUser, (float)$amount);
        $this->userService->updateUser($this->currentUser);
    }
    private function handleLogout(): void
    {
        $this->currentUser = null;
        echo "Logged out. See you soon!" . PHP_EOL;
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
        echo "4. Black_Jack" . PHP_EOL;
    }
}
