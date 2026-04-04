<?php

namespace Services;

use Services\UserService;
use Services\HistoryService;


use enums\coinSide;
use enums\gameType;
use Exceptions\BetExceedsBalanceException;
use Exceptions\MinBetLimitException;
use Games\AbstractGame;
use Games\CoinFlip;
use Games\Dice;
use Games\Slots;
use Helper\JsonStorage;
use Models\User;
use Exceptions\NegativeBetException;

class GameService
{
    private JsonStorage $userStorage; // TODO: удаляем переменные которые не используются
    private JsonStorage $historyStorage;
    private UserService $userService;
    private HistoryService $historyService;
    public function __construct($userService, $historyService)
    {
        $this->userStorage = new JsonStorage('storage/user.json');
        $this->historyStorage = new JsonStorage('storage/history.json');
        $this->userService = $userService;
        $this->historyService = $historyService;
    }
    public function validateBet(float $bet, User $user, AbstractGame $game): void
    {
        if ($bet < 0) {
            throw new NegativeBetException();
        }
        if ($bet > $user->getBalance()) {
            throw new BetExceedsBalanceException();
        }
        if ($bet < $game->getMinimalBet()) {
            throw new MinBetLimitException();
        }
    }
    public function chooseGame(gameType $gameType, float $bet): AbstractGame
    {
        return match ($gameType) {
            gameType::DICE => new Dice($bet),
            gameType::COIN_FLIP => new CoinFlip($bet),
            gameType::SLOTS => new Slots($bet)
        };
    }
    public function runGame(User $user, gameType $gameType, float $bet, mixed $additionalData): array
    {
        $game = $this->chooseGame($gameType, $bet, $additionalData);
        $this->validateBet($bet, $user, $game);
        $this->userService->withdraw($user, $bet);
        $result = $game->play();
        if ($result['is_won']) {
            $this->userService->deposit($user, $result['payout']);
        }
        $this->userService->updateUser($user);
        $this->historyService->logGame(
            $user->getUserId(),
            $gameType->name,
            $bet,
            $result['is_won'],
            $result['payout']
        );
        return $result;
    }
}
