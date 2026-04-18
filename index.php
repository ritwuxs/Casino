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
require_once 'Exceptions/UserExceptions/UserAlreadyExistsException.php';
require_once 'Exceptions/UserExceptions/UserNotFoundException.php';

require_once 'Enums/GameType.php';
require_once 'Enums/CoinSide.php';

require_once 'Games/AbstractGame.php';
require_once 'Games/Dice.php';
require_once 'Games/CoinFlip.php';
require_once 'Games/Slots.php';
require_once 'Games/BlackJack.php';

 // TODO: это уже в сервисе , удаляем
 // TODO: тоже самоe
// TODO: выносим в сервисы в конструкторы
 // TODO: инициализация сервисов уже в контроллере , удаляем
// TODO: инициализация сервисов уже в контроллере , удаляем
// TODO: инициализация сервисов уже в контроллере , удаляем


$controller = new \Controllers\CasinoController();

$controller->run();
