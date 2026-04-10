<?php

namespace Services;

use Services\UserService;
use Services\HistoryService;

use Helper\ReadConfig;
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
use Games\Black_Jack;

class GameService
{
    // DO: удаляем переменные которые не используются

    private UserService $userService;
    private HistoryService $historyService;
    private ReadConfig $config;

    public function __construct(UserService $userService, HistoryService $historyService,ReadConfig $config)
    {
        $this->userService = $userService;
        $this->historyService = $historyService;
        $this->config = $config;
    }

    public function shouldCheat(): bool
    {
        $isEnable = $this->config->get('CHEAT_MODE') == true;
        $persent = $this->config->get('CHEAT_PERCENT');
        if (!$isEnable) {
            return false;
        }
        return random_int(1, 100) <= $persent;
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
            GameType::SLOTS => new Slots($bet),
            GameType::BLACK_JACK => new Black_Jack($bet)
        };
    }

    public function runGame(User $user, GameType $gameType, float $bet): array
    {
        $game = $this->chooseGame($gameType, $bet);
        $this->validateBet($bet, $user, $game);
        $this->userService->withdraw($user, $bet);
        $result = $game->play();
        if ($result['is_won'] && $this->shouldCheat()) {
            $result['is_won'] = false;
            $result['payout'] = 0;
            $result['message'] = "Bad luck! Try again.";
        }
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
