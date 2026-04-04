<?php

namespace Games;

use enums\gameType;
use Games\AbstractGame;

class Slots extends AbstractGame
{
    public function __construct(float $bet, int $coficient = 10, float $minimalBet = 15)
    {
        parent::__construct($bet, $minimalBet, $coficient, gameType::SLOTS);
    }
    // TODO: (!!! Пока что делать не надо) - На будущее будем усложнять логику слотов
    public function play(): array
    {
        $slot1 = random_int(1, 5);
        $slot2 = random_int(1, 5);
        $slot3 = random_int(1, 5);
        $combination = [$slot1, $slot2, $slot3];
        if ($slot1 === $slot2 && $slot1 === $slot3) {
            $isWin = true;
            $payout = $this->bet * $this->coficient;
            $message = "JACKPOT! All numbers are the same!";
        } elseif ($slot1 === $slot2 || $slot1 === $slot3 || $slot2 === $slot3) {
            $isWin = true;
            $payout = $this->bet * ($this->coficient / 2);
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
