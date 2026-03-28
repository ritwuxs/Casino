<?php


require_once 'Models/User.php';
require_once 'Helper/JsonStorage.php';
require_once 'Services/UserService.php';
require_once 'Services/HistoryService.php';
require_once 'Exceptions/UserException.php';
require_once 'Exceptions/BetException.php';
require_once 'Games/AbstractGame.php';
require_once 'Games/Dice.php';
require_once 'Games/CoinFlip.php';
require_once 'Games/Slots.php';

use Exceptions\BetException;
use Helper\JsonStorage;
use Services\UserService;
use Services\HistoryService;
use Exceptions\UserException;
use Games\AbstractGame;
use Games\Dice;
use Games\CoinFlip;
use Games\Slots;

$userStorage = new JsonStorage('users.json');

$historyStorage = new JsonStorage('history.json');

$userService = new UserService($userStorage);

$historyService = new HistoryService($historyStorage);

$currentUser = null;
while ($currentUser === null) {
    echo "1. Sign in" . PHP_EOL;
    echo "2. Registration" . PHP_EOL;
    $action = readline("Action: ");
    try {
        if ($action === '1') {
            $name = readline("Enter login: ");
            $currentUser = $userService->login($name);
            echo "Welcome to the system, " . $currentUser->getName() . "!" . PHP_EOL;
        } elseif ($action === '2') {
            $name = readline("Come up with a name: ");
            $userService->registration($name);
            echo "You have successfully registered! Now log in." . PHP_EOL;
        } else {
            echo "Exit.." . PHP_EOL;
            break;
        }
    } catch (UserException $e) {
        echo "Error: " . $e->getMessage() . PHP_EOL;
    }
}
while (true) {
    echo "User: " . $currentUser->getName() . " | Bakance: " . $currentUser->getBalance() . " grn" . PHP_EOL;
    echo "------------------------" . PHP_EOL;
    echo "1. Play" . PHP_EOL;
    echo "2. To Deposit" . PHP_EOL;
    echo "3. Game History " . PHP_EOL;
    echo "4. See balance" . PHP_EOL;
    echo "5. Statistics" . PHP_EOL;
    echo "6. Exit" . PHP_EOL;
    $choise = readline("Choose action: ");
    switch ($choise) {
        case '1':
            try {
                $input = readline("Enter your bet: ");
                $bet = (float)$input;
                if ($bet < 0) {
                    throw new BetException("Bet can not be negative");
                }
                if ($bet > $currentUser->getBalance()) {
                    throw new BetException("Bet can not be bigger than balance");
                }
                if ($bet < 10) {
                    throw new BetException("Minimal bet is 10");
                }
            } catch (BetException $e) {
                echo "Error: " . $e->getMessage() . PHP_EOL;
                continue 2;
            }
            echo "What do you want to play?" . PHP_EOL;
            echo "1. Dice" . PHP_EOL;
            echo "2. Coin flip" . PHP_EOL;
            echo "3. Slots" . PHP_EOL;
            $game = null;
            $choise2 = readline("Choose game: ");
            switch ($choise2) {
                case '1':
                    $num = (int)readline("What number do we bet on? (1-6)? ");
                    $game = new Dice($bet, $num);
                    break;

                case '2':
                    echo "0 - Orel , 1 -  Reshka" . PHP_EOL;
                    $side = (int)readline("You choice: ");
                    $game = new CoinFlip($bet, $side);
                    break;

                case '3':
                    $game = new Slots($bet);
                    break;
            }
            if ($game) {
                $currentUser->withdraw($bet);
                $result = $game->play();
                echo $result['message'] . PHP_EOL;
                if ($result['isWin']) {
                    $currentUser->deposit($result['payout']);
                }
                $userService->updateUser($currentUser);

                $historyService->logGame(
                    $currentUser->getUserId(),
                    $choise2,
                    $bet,
                    $result['isWin'],
                    $result['payout']
                );
                readline("Your results: ");
            }
            break;

        case '2':
            echo "Enter amount: ";
            $amount = readLine("");
            $currentUser->deposit($amount);
            $userService->updateUser($currentUser);
            break;

        case '3':
            $historyService->showUserHistory($currentUser->getUserId());
            readline("Your results: ");
            break;

        case '4':
            echo "Your balance: " . $currentUser->getBalance() . " grn" . PHP_EOL;
            break;

        case '5':
            $history = $historyStorage->read();
            $myGames = array_filter($history, function ($g) use ($currentUser) {
                return isset($g['userId']) && $g['userId'] === $currentUser->getUserId();
            });
            $total = count($myGames);
            if ($total > 0) {
                $wins = count(array_filter($myGames, function ($g) {
                    return isset($g['isWin']) && $g['isWin'] === true;
                }));
                $winrate = round(($wins / $total) * 100, 2);
                echo "Your Winrate: $winrate % (games played: $total)" . PHP_EOL;
            } else {
                echo "There have been no games yet, the statistics are empty." . PHP_EOL;
            }

        case '6':
            echo "Exit";
            break;
    }
}
