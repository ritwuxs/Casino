<?php
require_once 'Helper/JsonStorage.php';
require_once 'Helper/ReadConfig.php';
require_once 'Models/User.php';
require_once 'Services/UserService.php';
require_once 'Services/AuthService.php';
require_once 'Services/HistoryService.php';
require_once 'Services/GameService.php';
require_once 'Controllers/CasinoController.php';
require_once 'Exceptions/FilesExceptions/FileDoNotExists.php';
require_once 'Enums/GameType.php';
require_once 'Enums/CoinSide.php';
require_once 'Exceptions/UserExceptions/UserAlreadyExistsException.php';
require_once 'Exceptions/UserExceptions/UserNotFoundException.php';
require_once 'Games/AbstractGame.php';
require_once 'Games/Dice.php';
require_once 'Games/CoinFlip.php';
require_once 'Games/Slots.php';
require_once 'Games/Black_Jack.php';

$userStorage = new \Helper\JsonStorage('storage/users.json');
$historyStorage = new \Helper\JsonStorage('storage/history.json');
$config = new \Helper\ReadConfig();
$userService = new \Services\UserService($userStorage);
$authService = new \Services\AuthService($userStorage);
$historyService = new \Services\HistoryService($historyStorage);
$gameService = new \Services\GameService($userService, $historyService, $config);
$controller = new \Controllers\CasinoController(
    $authService,
    $gameService,
    $historyService,
    $userService,
    $config
);
$controller->run();