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
require_once 'Exceptions/FilesExceptions/InvalidArgumentException.php';

require_once 'Exceptions/UserExceptions/UserAlreadyExistsException.php';
require_once 'Exceptions/UserExceptions/UserNotFoundException.php';
require_once 'Exceptions/UserExceptions/NegativeAmountException.php';
require_once 'Exceptions/UserExceptions/InsufficientFundsException.php';
require_once 'Exceptions/UserExceptions/InsufficientBalanceException.php';

require_once 'Exceptions/BetExceptions/MinBetLimitException.php';
require_once 'Exceptions/BetExceptions/NegativeBetException.php';
require_once 'Exceptions/BetExceptions/BetExceedsBalanceException.php';

require_once 'Exceptions/FaildRegistration.php';
require_once 'Exceptions/InvalidGameTypeException.php';


require_once 'Enums/GameType.php';
require_once 'Enums/CoinSide.php';

require_once 'Games/AbstractGame.php';
require_once 'Games/Dice.php';
require_once 'Games/CoinFlip.php';
require_once 'Games/Slots.php';
require_once 'Games/BlackJack.php';

// DO: это уже в сервисе , удаляем
// DO: тоже самоe
// DO: выносим в сервисы в конструкторы
// DO: инициализация сервисов уже в контроллере , удаляем
// DO: инициализация сервисов уже в контроллере , удаляем
// DO: инициализация сервисов уже в контроллере , удаляем


$controller = new \Controllers\CasinoController();

$controller->run();
