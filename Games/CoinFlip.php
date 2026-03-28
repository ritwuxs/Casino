<?php

namespace Games;

use Games\AbstractGame;

class CoinFlip extends AbstractGame
{
    private int $userChoice;
    // TODO: добавить поле coinSide (вынести в enum)
    public function __construct(float $bet, int $userChoice) 
    {
        parent::__construct($bet);
        $this->userChoice = $userChoice;
    }

    public function play(): array
    {
        $roll = random_int(0, 1);
        $isWin = ($roll === $this->userChoice);
        $payout = $isWin ? ($this->bet * 2) : 0;
        $message = $isWin
            ? "The Orel fell out.  $roll. You won $payout coins!"
            : "It's a pity, but Reshka fell out $roll.You lost the bet.";
        return [
            'isWin'   => $isWin,
            'payout'  => (float)$payout,
            'message' => $message,
            'roll'    => $roll
        ];
    }
}
