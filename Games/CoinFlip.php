<?php

namespace Games;

use Enums\CoinSide;
use enums\GameType;
use Games\AbstractGame;

class CoinFlip extends AbstractGame
{
    public function __construct(float $bet,  int $coficient = 2, float $minimalBet = 5)
    {
        parent::__construct($bet, $minimalBet, $coficient, GameType::COIN_FLIP);
    }

    public function play(): array
    {
        echo "0 - Orel , 1 -  Reshka" . PHP_EOL;
        $input = (int)readline("You choice: ");
        $userSite = CoinSide::from($input);
        $roll = random_int(0, 1);
        $winningSide = CoinSide::from($roll);
        $is_won = ($winningSide === $userSite);
        $payout = $is_won ? ($this->bet * $this->coefficient) : 0;
        $message = $is_won
            ? "Fell out:" . $winningSide->name . "\nYou won $payout grn!"
            : "Fell out:" . $winningSide->name . "You lost the bet.";
        return [
            'is_won'   => $is_won,
            'payout'  => (float)$payout,
            'message' => $message,
            'roll'    => $roll
        ];
    }
}
