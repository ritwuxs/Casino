<?php

namespace Services;

use Services\UserService;
use Services\HistoryService;


use enums\coinSide;
use enums\GameType;
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
    // DO: удаляем переменные которые не используются
   
    private UserService $userService;
    private HistoryService $historyService;
    public function __construct($userService, $historyService)
    {
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
    public function chooseGame(GameType $gameType, float $bet): AbstractGame
    {
        return match ($gameType) {
            GameType::DICE => new Dice($bet),
            GameType::COIN_FLIP => new CoinFlip($bet),
            GameType::SLOTS => new Slots($bet)
        };
    }
    public function runGame(User $user, gameType $gameType, float $bet): array
    {
        $game = $this->chooseGame($gameType, $bet);
        $this->validateBet($bet, $user, $game);
        $this->userService->withdraw($user, $bet);
        $result = $game->play();
        if ($result['is_won']) {
            $this->userService->deposit($user, $result['payout']);
        }
        $this->userService->updateUser($user);
        $this->historyService->logGame(
            $user->getId(),
            $gameType->name,
            $bet,
            $result['is_won'],
            $result['payout']
        );
        return $result;
    }
}
