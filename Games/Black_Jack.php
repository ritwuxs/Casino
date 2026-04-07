<?php

namespace Games;

use Enums\GameType;
use Games\AbstractGame;

class Black_Jack extends AbstractGame
{
    public function __construct(float $bet,  int $coficient = 20, float $minimalBet = 10)
    {
        parent::__construct($bet, $minimalBet, $coficient, GameType::COIN_FLIP);
    }
    public function play(): array
    {
        $playerPoints = random_int(2, 11) + random_int(2, 11);
        while (true) {
            echo "Your sum now: $playerPoints" . PHP_EOL;
            $choise = (int)readline("Do you want to 1 - take more, or 0 - stop?");
            if ($choise === 1) {
                $playerPoints += random_int(2, 11);
            }
            if ($choise === 0) {
                break;
            }
            if ($playerPoints > 21) {
                echo "You have lost :(" . PHP_EOL;
                break;
            }
        }
        $dealerPoints = 0;
        while ($dealerPoints < 17) {
            $dealerPoints += random_int(2, 11);
        }
        $is_won = false;
        $pay_out = 0;
        $message = "";
        if ($playerPoints > 21) {
            $message = "Bust! You have $playerPoints points. Dealer wins.";
        } elseif ($dealerPoints > 21 || $playerPoints > $dealerPoints) {
            $is_won = true;
            $pay_out = $this->bet * $this->coefficient;
            $message = "Win! Your $playerPoints vs Dealer $dealerPoints.";
        } elseif ($playerPoints === $dealerPoints) {
            $is_won = true; // Формально виграш, щоб сервіс зробив депозит
            $pay_out = $this->bet; // Повертаємо тільки те, що поставили
            $message = "Push! It's a tie ($playerPoints points). Bet returned.";
        }
        else{
            $message = "Lost! Your $playerPoints vs Dealer $dealerPoints.";
        }
        return [
         'is_won' => $is_won,
         'pay_out' => $pay_out,
         'message'=>$message,
         'roll' => $playerPoints

        ];
    }
}
