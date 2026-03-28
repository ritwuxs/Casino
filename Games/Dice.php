<?php

namespace Games;

use Games\AbstractGame;

class Dice extends AbstractGame
{
    private int $userNumber;
    public function __construct(float $bet, int $userNumber)
    {
        parent::__construct($bet);
        $this->userNumber = $userNumber;
    }
    public function play(): array
    {
        $roll = random_int(1, 6);
        $isWin = ($roll === $this->userNumber);
        $payout = $isWin ? ($this->bet * 6) : 0;
        $message = $isWin
            ? "Congratulations! It's out $roll. You won $payout coins!"
            : "It's a pity, but it fell out $roll.You lost the bet.";
        return [
            'isWin'   => $isWin,
            'payout'  => (float)$payout,
            'message' => $message,
            'roll'    => $roll
        ];
    }
}
