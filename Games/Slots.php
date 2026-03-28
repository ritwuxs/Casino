<?php

namespace Games;

use Games\AbstractGame;

class Slots extends AbstractGame
{
    public function __construct(float $bet )
    {
        parent::__construct($bet);
    }
    public function play(): array
    {
        $slot1 = random_int(1, 5);
        $slot2 = random_int(1, 5);
        $slot3 = random_int(1, 5);
        $combination = [$slot1, $slot2, $slot3];
        if ($slot1 === $slot2 && $slot1 === $slot3) {
            $isWin = true;
            $payout = $this->bet * 10;
            $message = "JACKPOT! All numbers are the same!";
        } elseif ($slot1 === $slot2 || $slot1 === $slot3 || $slot2 === $slot3) {
            $isWin = true;
            $payout = $this->bet * 5;
            $message = "Winning couple!";
        } else {
            $isWin = false;
            $payout = 0;
            $message = "Nothing fell out(";
        }
        $visual = "[ " . implode(" | ", $combination) . " ]";
        return [
            'isWin'   => $isWin,
            'payout'  => (float)$payout,
            'message' => "$visual - $message"
        ];
    }
}
