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
    public function logGame(int $userId, string $type, float $bet, bool $isWin, float $payout): void // DO: добавить типизацию параметров
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
    public function getHistory(User $user):array{
        $history = $this->storage->read(); // DO: вынесем получение истории в HistoryService, getUserHistory(int $userId)
        return array_filter($history, function ($g) use ($user) {
            return isset($g['user_id']) && $g['user_id'] === $user->getUserId();
        });
    }
    public function showUserStatistics(User $user): void
    {
        $myGames = $this->getHistory($user);
        $total = count($myGames);
        if ($total > 0) {
            $wins = count(array_filter($myGames, function ($g) {
                return isset($g['is_won']) && $g['is_won'] === true;
            }));
            $winrate = round(($wins / $total) * 100, 2);
            echo "Your Winrate: $winrate % (games played: $total)" . PHP_EOL;
        } else {
            echo "There have been no games yet, the statistics are empty." . PHP_EOL;
        }
    }
}
