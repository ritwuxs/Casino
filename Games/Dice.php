<?php

namespace Games;

use Enums\GameType;
use Games\AbstractGame;

class Dice extends AbstractGame
{
    public function __construct(float $bet, int $coficient = 6, float $minimalBet = 10)
    {
        parent::__construct($bet, $minimalBet, $coficient, GameType::DICE);
    }
    
    public function play(): array
    {
        $num = (int)readline("What number do we bet on? (1-6)? ");

        $roll = random_int(1, 6);
        $is_won = ($roll === $num);
        $payout = $is_won ? ($this->bet * $this->coefficient) : 0;
        $message = $is_won
            ? "Congratulations! It's out $roll. You won $payout coins!"
            : "It's a pity, but it fell out $roll.You lost the bet.";

        return [
            'is_won'   => $is_won,
            'payout'  => (float)$payout,
            'message' => $message,
            'roll'    => $roll
        ];
    }
}
