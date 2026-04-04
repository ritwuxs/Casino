<?php

namespace Games;

use enums\coinSide;
use enums\gameType;
use Games\AbstractGame;

class CoinFlip extends AbstractGame
{
    public function __construct(float $bet,  int $coficient = 2, float $minimalBet = 5)
    {
        parent::__construct($bet, $minimalBet, $coficient, gameType::COIN_FLIP);
    }

    public function play(): array
    {
        echo "0 - Orel , 1 -  Reshka" . PHP_EOL;
        $input = (int)readline("You choice: ");
        $userSite = coinSide::from($input);
        $roll = random_int(0, 1);
        $winningSide = coinSide::from($roll);
        $is_won = ($winningSide === $userSite);
        $payout = $is_won ? ($this->bet * $this->coficient) : 0;
        $message = $is_won
            ? "Fell out:" . $winningSide->name . "You won $payout grn!"
            : "Fell out:" . $winningSide->name . "You lost the bet.";
        return [
            'isWin'   => $is_won,
            'payout'  => (float)$payout,
            'message' => $message,
            'roll'    => $roll
        ];
    }
}
