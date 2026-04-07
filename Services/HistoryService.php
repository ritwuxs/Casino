<?php

namespace Services;

use Models\User;
use Helper\JsonStorage;

class HistoryService
{
    private JsonStorage $storage;
    public function __construct(JsonStorage $storage)
    {
        $this->storage = $storage;
    }
    public function logGame(int $userId, string $type, float $bet, bool $isWin, float $payout): void
    {
        $history = $this->storage->read();
        $history[] = [
            'user_id'   => $userId,
            'game_type' => $type,
            'bet'      => $bet,
            'is_won'    => $isWin,
            'pay_out'   => $payout,
            'date'     => date('Y-m-d H:i:s')
        ];
        $this->storage->write($history);
    }

    public function showUserHistory(int $userId): void
    {
        $allHistory = $this->storage->read();

        echo "=== Your game history ===" . PHP_EOL;
        echo "Game | Bet | Win | Status" . PHP_EOL;
        echo "---------------------------------" . PHP_EOL;
        $found = false;
        foreach ($allHistory as $record) {
            if (isset($record['user_id']) && $record['user_id'] === $userId) {
                $status = ($record['is_won'] ?? false) ? "Win" : "Loss";
                $game   = $record['game_type'] ?? "Unknown";
                $payout = $record['pay_out'] ?? 0;
                $bet    = $record['bet'] ?? 0;

                echo "{$game} | {$bet} | {$payout} | {$status}" . PHP_EOL;
                $found = true;
            }
        }

        if (!$found) {
            echo "You haven't played yet." . PHP_EOL;
        }
        echo "---------------------------------" . PHP_EOL;
    }
    public function getHistory(User $user): array
    {
        $history = $this->storage->read();
        return array_filter($history, function ($g) use ($user) {
            return isset($g['user_id']) && $g['user_id'] === $user->getId();
        });
    }

    // TODO: усложнить статистику, добавить больше данных
    // - сумма всех ставок
    // - средняя ставка
    // - в какие игры сколько играл
    // - Найприбутковіша гра
    // - Самая убыточная
    public function showUserStatistics(User $user): void
    {
        $myGames = $this->getHistory($user);
        $total = count($myGames);
        $sumOfBets = 0;
        $gameCount = [];
        $gameProfit = [];
        foreach ($myGames as $game) {
            $type = $game['gameType'];
            $bet = (float)$game['bet'];
            $payout = (float)$game['pay_out'];
            $sumOfBets += $bet;
            if (!isset($gameCount['type'])) {
                $gameCount[$type] = 0;
            }
            $gameCount[$type]++;
            if (!isset($gameProfit[$type])) {
                $gameProfit[$type] = 0;
            }
            $gameProfit[$type] += ($payout - $bet);
        }
        asort($gameProfit);
        $mostProfitable = array_key_first($gameProfit);
        $leastProfitable = array_key_last($gameProfit);
        $averegeBet = $sumOfBets / $total;


        if ($total > 0) {
            $wins = count(array_filter($myGames, function ($g) {
                return isset($g['is_won']) && $g['is_won'] === true;
            }));
            $winrate = round(($wins / $total) * 100, 2);
            echo "Your Winrate: $winrate % (games played: $total)" . PHP_EOL;
            echo "Your total sum of money: $sumOfBets grn:" . PHP_EOL;
            echo "Your averege bet is: round($averegeBet) grn" . PHP_EOL;
            echo "--- GAMES COUNT ---" . PHP_EOL;
            foreach ($gameCount as $name => $count) {
                echo "- $name: $count times" . PHP_EOL;
            }

            echo "--- PROFITABILITY ---" . PHP_EOL;
            echo "Most profitable game: $mostProfitable (" . $gameProfit[$mostProfitable] . " grn)" . PHP_EOL;
            echo "Most loss-making game: $leastProfitable (" . $gameProfit[$leastProfitable] . " grn)" . PHP_EOL;
        } else {
            echo "There have been no games yet, the statistics are empty." . PHP_EOL;
        }
    }
}
