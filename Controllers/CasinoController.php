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
use Exception\Exceptions\InvalidGameTypeException;

class CasinoController
{
    //DO: удаляем ненужные свойства
    // DO: удаляем ненужные свойства
    private ?User $currentUser = null;
    private AuthService $authService;
    private GameService $game;
    private HistoryService $history;
    private UserService $userService;
    public function __construct()
    {
        $storage = new \Helper\JsonStorage('storage/users.json');

        $this->authService = new AuthService(); // DO: сервисы инициализируем в контроллере
        $this->userService = new UserService();
        $this->history = new HistoryService();
        $this->game = new GameService();
    }
    public function run(): void // DO: обрабатываем все исключения
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
                    } elseif ($action === '0') {
                        echo "Exit.." . PHP_EOL;
                        break;
                    } else {
                        echo "Unknown action. Try again." . PHP_EOL;
                    }
                } catch (\Exception $e) {
                    echo "Error: " . $e->getMessage() . PHP_EOL;
                }
                continue;
            }
            $this->showMenu($this->currentUser);
            $choise = readline("Choose action: ");
            try {
                switch ($choise) {
                    case '1':
                        try {
                            $this->whatToPlay();
                            $typeGame = readline("Choose 1,2,3 or 4: ");
                            $selectedEnum = match ($typeGame) {
                                '1' => \Enums\GameType::DICE,
                                '2' => \Enums\GameType::COIN_FLIP,
                                '3' => \Enums\GameType::SLOTS,
                                '4' => \Enums\GameType::BLACK_JACK,
                                default => throw new InvalidGameTypeException()
                            };
                            if ($selectedEnum === null) {
                                throw new InvalidGameTypeException();
                            }
                            $bet = readline("Your bet: ");

                            $result = $this->game->runGame($this->currentUser, $selectedEnum, (float)$bet);
                            echo $result['message'] . PHP_EOL;
                            break;
                        } catch (InvalidGameTypeException) {
                            echo "WARNING: " . $e->getMessage() . PHP_EOL;
                        } catch (InsufficientFundsException $e) {
                            echo "There is little money: " . $e->getMessage() . PHP_EOL;
                        } catch (\Exception $e) {
                            echo "An error occurred: " . $e->getMessage() . PHP_EOL;
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
                    default:
                        echo "Invalid option. Please choose 1-7." . PHP_EOL;
                }
            } catch (InvalidGameTypeException) {
                echo "WARNING: " . $e->getMessage() . PHP_EOL;
            } catch (\Exception\Exceptions\InsufficientFundsException $e) {
                echo "Balance Error: " . $e->getMessage() . PHP_EOL;
            } catch (\Exception $e) {
                echo "An unexpected error occurred: " . $e->getMessage() . PHP_EOL;
            }
        }
        readline("Press Enter to continue...");
    }
    public function handleDeposit(): void
    {
        try {
            echo "Enter amount: ";
            $amount = readLine("");
            $this->userService->deposit($this->currentUser, (float)$amount);
            $this->userService->updateUser($this->currentUser);
            echo "Balance successfully topped up!" . PHP_EOL;
        } catch (NegativeAmountException $e) {
            echo "Error: Amount cannot be negative." . PHP_EOL;
        } catch (\Exception $e) {
            echo "Error while replenishing: " . $e->getMessage() . PHP_EOL;
        }
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
